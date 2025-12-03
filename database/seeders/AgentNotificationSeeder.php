<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\AgentNotification;
use Illuminate\Database\Seeder;

class AgentNotificationSeeder extends Seeder
{
    public function run(): void
    {
        $agents = Agent::all();
        
        if ($agents->isEmpty()) {
            return;
        }

        $notificationTypes = ['payment', 'commission', 'balance', 'system', 'promo'];
        $notifications = [
            ['type' => 'payment', 'title' => 'Pembayaran Diterima', 'message' => 'Pembayaran dari customer telah diterima'],
            ['type' => 'commission', 'title' => 'Komisi Baru', 'message' => 'Anda mendapat komisi dari penjualan voucher'],
            ['type' => 'balance', 'title' => 'Saldo Ditambahkan', 'message' => 'Saldo Anda telah ditambahkan Rp 500.000'],
            ['type' => 'system', 'title' => 'Pemeliharaan Sistem', 'message' => 'Sistem akan maintenance pada tanggal 15 Desember'],
            ['type' => 'promo', 'title' => 'Promo Spesial', 'message' => 'Dapatkan bonus komisi 10% untuk penjualan voucher minggu ini'],
        ];

        foreach ($agents as $agent) {
            // Buat beberapa notifikasi untuk setiap agent
            for ($i = 0; $i < rand(3, 7); $i++) {
                $notification = $notifications[array_rand($notifications)];
                
                AgentNotification::create([
                    'agent_id' => $agent->id,
                    'notification_type' => $notification['type'],
                    'title' => $notification['title'],
                    'message' => $notification['message'],
                    'is_read' => rand(0, 1),
                    'created_at' => now()->subDays(rand(0, 30)),
                ]);
            }
        }
    }
}
