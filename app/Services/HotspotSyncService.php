<?php

namespace App\Services;

use App\Models\HotspotProfile;
use App\Models\HotspotVoucher;
use App\Models\HotspotSyncLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RouterOS\Query;

class HotspotSyncService
{
    protected MikrotikService $mikrotik;

    public function __construct(MikrotikService $mikrotik)
    {
        $this->mikrotik = $mikrotik;
    }

    // ==================== PROFILE SYNC ====================

    /**
     * Pull profiles from Mikrotik to Database
     */
    public function pullProfiles(): array
    {
        $result = ['created' => 0, 'updated' => 0, 'failed' => 0, 'errors' => []];

        if (!$this->mikrotik->isConnected()) {
            $result['errors'][] = 'Mikrotik not connected';
            $this->logSync('profile', 'pull', 'failed', $result);
            return $result;
        }

        try {
            $profiles = $this->mikrotik->getHotspotProfiles();

            foreach ($profiles as $profile) {
                try {
                    $speeds = $this->mikrotik->parseRateLimit($profile['rate_limit'] ?? '');
                    
                    $data = [
                        'mikrotik_id' => $profile['id'],
                        'rate_limit' => $profile['rate_limit'],
                        'upload_speed' => $speeds['upload'],
                        'download_speed' => $speeds['download'],
                        'shared_users' => $profile['shared_users'] ?? 1,
                        'session_timeout' => $profile['session_timeout'],
                        'idle_timeout' => $profile['idle_timeout'],
                        'keepalive_timeout' => $profile['keepalive_timeout'],
                        'address_pool' => $profile['address_pool'],
                        'synced' => true,
                        'last_synced_at' => now(),
                    ];

                    $existing = HotspotProfile::where('name', $profile['name'])->first();

                    if ($existing) {
                        $existing->update($data);
                        $result['updated']++;
                    } else {
                        HotspotProfile::create(array_merge($data, ['name' => $profile['name']]));
                        $result['created']++;
                    }
                } catch (\Exception $e) {
                    $result['failed']++;
                    $result['errors'][] = "Profile {$profile['name']}: {$e->getMessage()}";
                }
            }

            $result['total'] = count($profiles);
            $this->logSync('profile', 'pull', $result['failed'] > 0 ? 'partial' : 'success', $result);

        } catch (\Exception $e) {
            $result['errors'][] = $e->getMessage();
            $this->logSync('profile', 'pull', 'failed', $result);
        }

        return $result;
    }

    /**
     * Push profiles from Database to Mikrotik
     */
    public function pushProfiles(): array
    {
        $result = ['created' => 0, 'updated' => 0, 'failed' => 0, 'errors' => []];

        if (!$this->mikrotik->isConnected()) {
            $result['errors'][] = 'Mikrotik not connected';
            $this->logSync('profile', 'push', 'failed', $result);
            return $result;
        }

        try {
            $profiles = HotspotProfile::where('synced', false)->orWhereNull('mikrotik_id')->get();
            $mikrotikProfiles = collect($this->mikrotik->getHotspotProfiles())->keyBy('name');

            foreach ($profiles as $profile) {
                try {
                    $exists = $mikrotikProfiles->has($profile->name);

                    if ($exists) {
                        // Update existing profile
                        $this->updateMikrotikProfile($profile, $mikrotikProfiles[$profile->name]['id']);
                        $result['updated']++;
                    } else {
                        // Create new profile
                        $this->createMikrotikProfile($profile);
                        $result['created']++;
                    }

                    $profile->update(['synced' => true, 'last_synced_at' => now()]);

                } catch (\Exception $e) {
                    $result['failed']++;
                    $result['errors'][] = "Profile {$profile->name}: {$e->getMessage()}";
                }
            }

            $result['total'] = $profiles->count();
            $this->logSync('profile', 'push', $result['failed'] > 0 ? 'partial' : 'success', $result);

        } catch (\Exception $e) {
            $result['errors'][] = $e->getMessage();
            $this->logSync('profile', 'push', 'failed', $result);
        }

        return $result;
    }

    /**
     * Create profile in Mikrotik
     */
    protected function createMikrotikProfile(HotspotProfile $profile): void
    {
        $client = $this->getClient();
        
        $query = new Query('/ip/hotspot/user/profile/add');
        $query->equal('name', $profile->name);
        
        if ($profile->rate_limit) {
            $query->equal('rate-limit', $profile->rate_limit);
        }
        if ($profile->shared_users) {
            $query->equal('shared-users', (string) $profile->shared_users);
        }
        if ($profile->session_timeout) {
            $query->equal('session-timeout', $profile->session_timeout);
        }
        if ($profile->idle_timeout) {
            $query->equal('idle-timeout', $profile->idle_timeout);
        }
        if ($profile->address_pool) {
            $query->equal('address-pool', $profile->address_pool);
        }

        $response = $client->query($query)->read();
        
        // Get the created profile ID
        $profiles = $this->mikrotik->getHotspotProfiles();
        $created = collect($profiles)->firstWhere('name', $profile->name);
        
        if ($created) {
            $profile->update(['mikrotik_id' => $created['id']]);
        }
    }

    /**
     * Update profile in Mikrotik
     */
    protected function updateMikrotikProfile(HotspotProfile $profile, string $mikrotikId): void
    {
        $client = $this->getClient();
        
        $query = new Query('/ip/hotspot/user/profile/set');
        $query->equal('.id', $mikrotikId);
        
        if ($profile->rate_limit) {
            $query->equal('rate-limit', $profile->rate_limit);
        }
        if ($profile->shared_users) {
            $query->equal('shared-users', (string) $profile->shared_users);
        }
        if ($profile->session_timeout) {
            $query->equal('session-timeout', $profile->session_timeout);
        }
        if ($profile->idle_timeout) {
            $query->equal('idle-timeout', $profile->idle_timeout);
        }

        $client->query($query)->read();
        $profile->update(['mikrotik_id' => $mikrotikId]);
    }

    /**
     * Delete profile from Mikrotik
     */
    public function deleteProfileFromMikrotik(HotspotProfile $profile): bool
    {
        if (!$this->mikrotik->isConnected() || !$profile->mikrotik_id) {
            return false;
        }

        try {
            $client = $this->getClient();
            $query = new Query('/ip/hotspot/user/profile/remove');
            $query->equal('.id', $profile->mikrotik_id);
            $client->query($query)->read();
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to delete profile from Mikrotik: ' . $e->getMessage());
            return false;
        }
    }

    // ==================== VOUCHER SYNC ====================

    /**
     * Pull vouchers from Mikrotik to Database
     */
    public function pullVouchers(): array
    {
        $result = ['created' => 0, 'updated' => 0, 'failed' => 0, 'errors' => []];

        if (!$this->mikrotik->isConnected()) {
            $result['errors'][] = 'Mikrotik not connected';
            $this->logSync('voucher', 'pull', 'failed', $result);
            return $result;
        }

        try {
            $users = $this->mikrotik->getHotspotUsers();
            $profiles = HotspotProfile::pluck('id', 'name');

            foreach ($users as $user) {
                try {
                    $profileId = $profiles[$user['profile']] ?? null;
                    
                    $data = [
                        'mikrotik_id' => $user['id'],
                        'profile_id' => $profileId,
                        'password' => $user['password'] ?? '',
                        'profile_name' => $user['profile'],
                        'limit_uptime' => $user['limit_uptime'],
                        'limit_bytes_total' => $this->parseBytes($user['limit_bytes_total']),
                        'limit_bytes_in' => $this->parseBytes($user['limit_bytes_in']),
                        'limit_bytes_out' => $this->parseBytes($user['limit_bytes_out']),
                        'server' => $user['server'] ?? 'all',
                        'mac_address' => $user['mac_address'],
                        'comment' => $user['comment'],
                        'status' => $user['disabled'] ? 'disabled' : 'unused',
                        'synced' => true,
                        'last_synced_at' => now(),
                    ];

                    $existing = HotspotVoucher::where('username', $user['name'])->first();

                    if ($existing) {
                        $existing->update($data);
                        $result['updated']++;
                    } else {
                        HotspotVoucher::create(array_merge($data, ['username' => $user['name']]));
                        $result['created']++;
                    }
                } catch (\Exception $e) {
                    $result['failed']++;
                    $result['errors'][] = "Voucher {$user['name']}: {$e->getMessage()}";
                }
            }

            $result['total'] = count($users);
            $this->logSync('voucher', 'pull', $result['failed'] > 0 ? 'partial' : 'success', $result);

        } catch (\Exception $e) {
            $result['errors'][] = $e->getMessage();
            $this->logSync('voucher', 'pull', 'failed', $result);
        }

        return $result;
    }

    /**
     * Push vouchers from Database to Mikrotik
     */
    public function pushVouchers(): array
    {
        $result = ['created' => 0, 'updated' => 0, 'failed' => 0, 'errors' => []];

        if (!$this->mikrotik->isConnected()) {
            $result['errors'][] = 'Mikrotik not connected';
            $this->logSync('voucher', 'push', 'failed', $result);
            return $result;
        }

        try {
            $vouchers = HotspotVoucher::where('synced', false)->orWhereNull('mikrotik_id')->get();
            $mikrotikUsers = collect($this->mikrotik->getHotspotUsers())->keyBy('name');

            foreach ($vouchers as $voucher) {
                try {
                    $exists = $mikrotikUsers->has($voucher->username);

                    if ($exists) {
                        $this->updateMikrotikVoucher($voucher, $mikrotikUsers[$voucher->username]['id']);
                        $result['updated']++;
                    } else {
                        $this->createMikrotikVoucher($voucher);
                        $result['created']++;
                    }

                    $voucher->update(['synced' => true, 'last_synced_at' => now()]);

                } catch (\Exception $e) {
                    $result['failed']++;
                    $result['errors'][] = "Voucher {$voucher->username}: {$e->getMessage()}";
                }
            }

            $result['total'] = $vouchers->count();
            $this->logSync('voucher', 'push', $result['failed'] > 0 ? 'partial' : 'success', $result);

        } catch (\Exception $e) {
            $result['errors'][] = $e->getMessage();
            $this->logSync('voucher', 'push', 'failed', $result);
        }

        return $result;
    }

    /**
     * Generate vouchers and sync to Mikrotik
     */
    public function generateVouchers(array $options): array
    {
        set_time_limit(0); // Prevent timeout during Mikrotik sync
        $result = ['created' => 0, 'failed' => 0, 'vouchers' => [], 'errors' => []];

        $quantity = $options['quantity'] ?? 1;
        $profileId = $options['profile_id'] ?? null;
        $prefix = $options['prefix'] ?? 'VC';
        $length = $options['length'] ?? 6;
        $passwordLength = $options['password_length'] ?? 6;
        $limitUptime = $options['limit_uptime'] ?? null;
        $limitBytes = $options['limit_bytes'] ?? null;
        $server = $options['server'] ?? 'all';
        $comment = $options['comment'] ?? 'Generated by Gembok';
        $syncToMikrotik = $options['sync_to_mikrotik'] ?? true;

        $profile = $profileId ? HotspotProfile::find($profileId) : null;
        $profileName = $profile ? $profile->name : ($options['profile_name'] ?? 'default');

        DB::beginTransaction();

        try {
            for ($i = 0; $i < $quantity; $i++) {
                $username = $prefix . strtoupper(Str::random($length));
                $password = strtoupper(Str::random($passwordLength));

                // Ensure unique username
                while (HotspotVoucher::where('username', $username)->exists()) {
                    $username = $prefix . strtoupper(Str::random($length));
                }

                $voucher = HotspotVoucher::create([
                    'profile_id' => $profileId,
                    'username' => $username,
                    'password' => $password,
                    'profile_name' => $profileName,
                    'limit_uptime' => $limitUptime,
                    'limit_bytes_total' => $limitBytes,
                    'server' => $server,
                    'comment' => $comment,
                    'status' => 'unused',
                    'synced' => false,
                ]);

                if ($syncToMikrotik && $this->mikrotik->isConnected()) {
                    try {
                        $this->createMikrotikVoucher($voucher);
                        $voucher->update(['synced' => true, 'last_synced_at' => now()]);
                    } catch (\Exception $e) {
                        $result['errors'][] = "Failed to sync {$username}: {$e->getMessage()}";
                    }
                }

                $result['vouchers'][] = [
                    'id' => $voucher->id,
                    'username' => $username,
                    'password' => $password,
                    'profile' => $profileName,
                ];
                $result['created']++;
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            $result['errors'][] = $e->getMessage();
            $result['failed'] = $quantity - $result['created'];
        }

        return $result;
    }

    /**
     * Create voucher in Mikrotik
     */
    protected function createMikrotikVoucher(HotspotVoucher $voucher): void
    {
        $client = $this->getClient();
        
        $query = new Query('/ip/hotspot/user/add');
        $query->equal('name', $voucher->username);
        $query->equal('password', $voucher->password);
        $query->equal('profile', $voucher->profile_name ?? 'default');
        $query->equal('server', $voucher->server ?? 'all');
        
        if ($voucher->limit_uptime) {
            $query->equal('limit-uptime', $voucher->limit_uptime);
        }
        if ($voucher->limit_bytes_total) {
            $query->equal('limit-bytes-total', (string) $voucher->limit_bytes_total);
        }
        if ($voucher->comment) {
            $query->equal('comment', $voucher->comment);
        }

        $client->query($query)->read();

        // Get the created user ID
        $users = $this->mikrotik->getHotspotUsers();
        $created = collect($users)->firstWhere('name', $voucher->username);
        
        if ($created) {
            $voucher->update(['mikrotik_id' => $created['id']]);
        }
    }

    /**
     * Update voucher in Mikrotik
     */
    protected function updateMikrotikVoucher(HotspotVoucher $voucher, string $mikrotikId): void
    {
        $client = $this->getClient();
        
        $query = new Query('/ip/hotspot/user/set');
        $query->equal('.id', $mikrotikId);
        $query->equal('password', $voucher->password);
        $query->equal('profile', $voucher->profile_name ?? 'default');
        
        if ($voucher->limit_uptime) {
            $query->equal('limit-uptime', $voucher->limit_uptime);
        }
        if ($voucher->limit_bytes_total) {
            $query->equal('limit-bytes-total', (string) $voucher->limit_bytes_total);
        }

        $client->query($query)->read();
        $voucher->update(['mikrotik_id' => $mikrotikId]);
    }

    /**
     * Delete voucher from Mikrotik
     */
    public function deleteVoucherFromMikrotik(HotspotVoucher $voucher): bool
    {
        if (!$this->mikrotik->isConnected()) {
            return false;
        }

        try {
            $client = $this->getClient();
            
            // Find by username if mikrotik_id not available
            if (!$voucher->mikrotik_id) {
                $users = $this->mikrotik->getHotspotUsers();
                $user = collect($users)->firstWhere('name', $voucher->username);
                if (!$user) {
                    return true; // Already deleted
                }
                $voucher->mikrotik_id = $user['id'];
            }

            $query = new Query('/ip/hotspot/user/remove');
            $query->equal('.id', $voucher->mikrotik_id);
            $client->query($query)->read();
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to delete voucher from Mikrotik: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete multiple vouchers
     */
    public function deleteVouchers(array $ids, bool $deleteFromMikrotik = true): array
    {
        $result = ['deleted' => 0, 'failed' => 0, 'errors' => []];

        $vouchers = HotspotVoucher::whereIn('id', $ids)->get();

        foreach ($vouchers as $voucher) {
            try {
                if ($deleteFromMikrotik) {
                    $this->deleteVoucherFromMikrotik($voucher);
                }
                $voucher->delete();
                $result['deleted']++;
            } catch (\Exception $e) {
                $result['failed']++;
                $result['errors'][] = "Voucher {$voucher->username}: {$e->getMessage()}";
            }
        }

        return $result;
    }

    // ==================== FULL SYNC ====================

    /**
     * Full sync (both directions)
     */
    public function fullSync(string $type = 'all', string $conflictResolution = 'mikrotik'): array
    {
        $result = [
            'profiles' => ['pull' => [], 'push' => []],
            'vouchers' => ['pull' => [], 'push' => []],
        ];

        if ($type === 'all' || $type === 'profile') {
            if ($conflictResolution === 'mikrotik') {
                $result['profiles']['pull'] = $this->pullProfiles();
                $result['profiles']['push'] = $this->pushProfiles();
            } else {
                $result['profiles']['push'] = $this->pushProfiles();
                $result['profiles']['pull'] = $this->pullProfiles();
            }
        }

        if ($type === 'all' || $type === 'voucher') {
            if ($conflictResolution === 'mikrotik') {
                $result['vouchers']['pull'] = $this->pullVouchers();
                $result['vouchers']['push'] = $this->pushVouchers();
            } else {
                $result['vouchers']['push'] = $this->pushVouchers();
                $result['vouchers']['pull'] = $this->pullVouchers();
            }
        }

        return $result;
    }

    // ==================== HELPERS ====================

    protected function getClient()
    {
        $reflection = new \ReflectionClass($this->mikrotik);
        $property = $reflection->getProperty('client');
        $property->setAccessible(true);
        return $property->getValue($this->mikrotik);
    }

    protected function parseBytes($value): ?int
    {
        if (empty($value)) {
            return null;
        }
        
        if (is_numeric($value)) {
            return (int) $value;
        }

        $value = strtolower(trim($value));
        $number = (float) preg_replace('/[^0-9.]/', '', $value);

        if (strpos($value, 'g') !== false) {
            return (int) ($number * 1073741824);
        } elseif (strpos($value, 'm') !== false) {
            return (int) ($number * 1048576);
        } elseif (strpos($value, 'k') !== false) {
            return (int) ($number * 1024);
        }

        return (int) $number;
    }

    protected function logSync(string $type, string $direction, string $status, array $result): void
    {
        HotspotSyncLog::create([
            'type' => $type,
            'direction' => $direction,
            'status' => $status,
            'total_items' => $result['total'] ?? 0,
            'created' => $result['created'] ?? 0,
            'updated' => $result['updated'] ?? 0,
            'deleted' => $result['deleted'] ?? 0,
            'failed' => $result['failed'] ?? 0,
            'error_message' => !empty($result['errors']) ? implode('; ', $result['errors']) : null,
            'details' => $result,
            'user_id' => auth()->id(),
        ]);
    }
}
