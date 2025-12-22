<?php

namespace App\Services;

use App\Models\VoucherPurchase;
use App\Models\VoucherPricing;
use App\Models\IntegrationSetting;
use Illuminate\Support\Facades\Log;

class VoucherService
{
    protected $mikrotikService;
    protected $radiusService;

    public function __construct()
    {
        $this->mikrotikService = new MikrotikService();
        $this->radiusService = new RadiusService();
    }

    /**
     * Create new voucher purchase
     */
    public function createPurchase(array $data): VoucherPurchase
    {
        $pricing = VoucherPricing::findOrFail($data['pricing_id']);

        $purchase = VoucherPurchase::create([
            'order_number' => VoucherPurchase::generateOrderNumber(),
            'pricing_id' => $pricing->id,
            'customer_name' => $data['customer_name'],
            'customer_phone' => $this->formatPhone($data['customer_phone']),
            'customer_email' => $data['customer_email'] ?? null,
            'amount' => $pricing->customer_price,
            'duration_hours' => $pricing->duration,
            'status' => 'pending',
            'payment_method' => $data['payment_method'] ?? 'manual',
        ]);

        // Generate voucher credentials
        $purchase->generateVoucherCredentials();

        Log::info('Voucher purchase created', ['order_number' => $purchase->order_number]);

        return $purchase;
    }

    /**
     * Process payment callback and activate voucher
     */
    public function processPayment(VoucherPurchase $purchase, string $transactionId = null): bool
    {
        if ($purchase->status !== 'pending') {
            return false;
        }

        $purchase->update([
            'status' => 'paid',
            'paid_at' => now(),
            'payment_transaction_id' => $transactionId,
        ]);

        // Activate voucher and sync to Mikrotik/RADIUS
        return $this->activateVoucher($purchase);
    }

    /**
     * Activate voucher and sync to network devices
     */
    public function activateVoucher(VoucherPurchase $purchase): bool
    {
        try {
            // Activate the voucher
            $purchase->activate();

            // Sync to Mikrotik Hotspot
            $mikrotikSynced = $this->syncToMikrotik($purchase);
            
            // Sync to RADIUS
            $radiusSynced = $this->syncToRadius($purchase);

            // Send WhatsApp notification
            $waSent = $this->sendWhatsAppNotification($purchase);

            $purchase->update([
                'synced_to_mikrotik' => $mikrotikSynced,
                'synced_to_radius' => $radiusSynced,
                'wa_sent' => $waSent,
            ]);

            Log::info('Voucher activated', [
                'order_number' => $purchase->order_number,
                'mikrotik' => $mikrotikSynced,
                'radius' => $radiusSynced,
                'whatsapp' => $waSent,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Voucher activation failed', [
                'order_number' => $purchase->order_number,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Sync voucher to Mikrotik Hotspot
     */
    protected function syncToMikrotik(VoucherPurchase $purchase): bool
    {
        $setting = IntegrationSetting::mikrotik();
        
        if (!$setting || !$setting->isActive()) {
            Log::info('Mikrotik integration not active, skipping sync');
            return false;
        }

        try {
            if (!$this->mikrotikService->isConnected()) {
                Log::warning('Mikrotik not connected');
                return false;
            }

            // Create hotspot user
            $result = $this->mikrotikService->createHotspotUser([
                'username' => $purchase->voucher_username,
                'password' => $purchase->voucher_password,
                'profile' => $this->getHotspotProfile($purchase->duration_hours),
                'limit_uptime' => $purchase->duration_hours . 'h',
                'comment' => "Voucher: {$purchase->order_number} - {$purchase->customer_name}",
            ]);

            return $result !== false;
        } catch (\Exception $e) {
            Log::error('Mikrotik sync failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Sync voucher to RADIUS
     */
    protected function syncToRadius(VoucherPurchase $purchase): bool
    {
        $setting = IntegrationSetting::radius();
        
        if (!$setting || !$setting->isActive()) {
            Log::info('RADIUS integration not active, skipping sync');
            return false;
        }

        try {
            if (!$this->radiusService->isEnabled()) {
                Log::warning('RADIUS not enabled');
                return false;
            }

            // Create RADIUS user
            $result = $this->radiusService->createUser(
                $purchase->voucher_username,
                $purchase->voucher_password,
                [
                    'Session-Timeout' => $purchase->duration_hours * 3600,
                ]
            );

            // Assign to group based on duration
            if ($result) {
                $groupName = $this->getRadiusGroup($purchase->duration_hours);
                $this->radiusService->assignGroup($purchase->voucher_username, $groupName);
            }

            return $result !== false;
        } catch (\Exception $e) {
            Log::error('RADIUS sync failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Send WhatsApp notification with voucher details
     */
    protected function sendWhatsAppNotification(VoucherPurchase $purchase): bool
    {
        $setting = IntegrationSetting::whatsapp();
        
        if (!$setting || !$setting->isActive()) {
            Log::info('WhatsApp integration not active, skipping notification');
            return false;
        }

        try {
            $message = $this->buildWhatsAppMessage($purchase);
            
            $apiUrl = $setting->getConfig('api_url');
            $apiKey = $setting->getConfig('api_key');

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, rtrim($apiUrl, '/') . '/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
                'target' => $purchase->customer_phone,
                'message' => $message,
            ]));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: ' . $apiKey,
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            return $httpCode === 200;
        } catch (\Exception $e) {
            Log::error('WhatsApp notification failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Build WhatsApp message
     */
    protected function buildWhatsAppMessage(VoucherPurchase $purchase): string
    {
        $expiresAt = $purchase->expires_at ? $purchase->expires_at->format('d/m/Y H:i') : '-';
        
        return "🎫 *VOUCHER HOTSPOT*\n\n" .
               "Halo {$purchase->customer_name}!\n" .
               "Terima kasih atas pembelian voucher.\n\n" .
               "📋 *Detail Voucher:*\n" .
               "Order: {$purchase->order_number}\n" .
               "Durasi: {$purchase->duration_hours} Jam\n" .
               "Berlaku s/d: {$expiresAt}\n\n" .
               "🔐 *Login Hotspot:*\n" .
               "Username: `{$purchase->voucher_username}`\n" .
               "Password: `{$purchase->voucher_password}`\n\n" .
               "Atau gunakan kode voucher:\n" .
               "📌 *{$purchase->voucher_code}*\n\n" .
               "Hubungi kami jika ada kendala.\n" .
               "Terima kasih! 🙏";
    }

    /**
     * Get Mikrotik hotspot profile based on duration
     */
    protected function getHotspotProfile(int $hours): string
    {
        if ($hours <= 1) return 'voucher-1jam';
        if ($hours <= 3) return 'voucher-3jam';
        if ($hours <= 6) return 'voucher-6jam';
        if ($hours <= 12) return 'voucher-12jam';
        if ($hours <= 24) return 'voucher-1hari';
        if ($hours <= 72) return 'voucher-3hari';
        if ($hours <= 168) return 'voucher-7hari';
        return 'voucher-30hari';
    }

    /**
     * Get RADIUS group based on duration
     */
    protected function getRadiusGroup(int $hours): string
    {
        if ($hours <= 1) return 'voucher-1h';
        if ($hours <= 3) return 'voucher-3h';
        if ($hours <= 6) return 'voucher-6h';
        if ($hours <= 12) return 'voucher-12h';
        if ($hours <= 24) return 'voucher-24h';
        if ($hours <= 72) return 'voucher-72h';
        if ($hours <= 168) return 'voucher-168h';
        return 'voucher-720h';
    }

    /**
     * Format phone number to international format
     */
    protected function formatPhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        if (substr($phone, 0, 2) === '08') {
            $phone = '62' . substr($phone, 1);
        } elseif (substr($phone, 0, 1) === '8') {
            $phone = '62' . $phone;
        }
        
        return $phone;
    }

    /**
     * Manual activation (for admin)
     */
    public function manualActivate(VoucherPurchase $purchase): bool
    {
        $purchase->update([
            'status' => 'paid',
            'paid_at' => now(),
            'payment_method' => 'manual',
        ]);

        return $this->activateVoucher($purchase);
    }
}
