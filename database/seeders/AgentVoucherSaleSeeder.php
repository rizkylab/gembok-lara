<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\AgentVoucherSale;
use Illuminate\Database\Seeder;

class AgentVoucherSaleSeeder extends Seeder
{
    public function run(): void
    {
        $agents = Agent::all();
        
        if ($agents->isEmpty()) {
            return;
        }

        $packages = [
            ['id' => 'PKG-1H', 'name' => '1 Hour', 'price' => 5000, 'agent_price' => 4500],
            ['id' => 'PKG-3H', 'name' => '3 Hours', 'price' => 10000, 'agent_price' => 9000],
            ['id' => 'PKG-1D', 'name' => '1 Day', 'price' => 15000, 'agent_price' => 13500],
            ['id' => 'PKG-7D', 'name' => '7 Days', 'price' => 50000, 'agent_price' => 45000],
        ];

        $statuses = ['active', 'used', 'expired'];

        foreach ($agents as $agent) {
            // Buat beberapa penjualan voucher untuk setiap agent
            for ($i = 0; $i < rand(5, 15); $i++) {
                $package = $packages[array_rand($packages)];
                $status = $statuses[array_rand($statuses)];
                
                AgentVoucherSale::create([
                    'agent_id' => $agent->id,
                    'voucher_code' => 'VCH-' . strtoupper(substr(md5(time() . $i . $agent->id), 0, 8)),
                    'package_id' => $package['id'],
                    'package_name' => $package['name'],
                    'customer_phone' => '0812' . rand(10000000, 99999999),
                    'customer_name' => 'Customer ' . rand(1, 100),
                    'price' => $package['price'],
                    'agent_price' => $package['agent_price'],
                    'commission' => $package['price'] - $package['agent_price'],
                    'commission_amount' => $package['price'] - $package['agent_price'],
                    'status' => $status,
                    'sold_at' => now()->subDays(rand(1, 30)),
                    'used_at' => $status === 'used' ? now()->subDays(rand(0, 15)) : null,
                    'notes' => rand(0, 1) ? 'Penjualan normal' : null,
                ]);
            }
        }
    }
}
