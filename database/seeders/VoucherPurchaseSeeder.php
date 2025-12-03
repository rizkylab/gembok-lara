<?php

namespace Database\Seeders;

use App\Models\VoucherPurchase;
use App\Models\VoucherPricing;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VoucherPurchaseSeeder extends Seeder
{
    public function run(): void
    {
        $pricings = VoucherPricing::all();

        if ($pricings->count() > 0) {
            // Create 20 dummy purchases
            for ($i = 0; $i < 20; $i++) {
                $pricing = $pricings->random();
                $status = collect(['completed', 'pending', 'failed'])->random();
                $quantity = rand(1, 5);
                
                $voucherData = [];
                if ($status === 'completed') {
                    for ($j = 0; $j < $quantity; $j++) {
                        $voucherData[] = [
                            'code' => 'VCH-' . Str::upper(Str::random(8)),
                            'password' => Str::random(8),
                        ];
                    }
                }
                
                VoucherPurchase::create([
                    'customer_name' => 'Customer ' . rand(1, 100),
                    'customer_phone' => '08' . rand(1000000000, 9999999999),
                    'amount' => $pricing->customer_price * $quantity,
                    'description' => 'Pembelian voucher ' . $pricing->package_name,
                    'type' => 'voucher',
                    'voucher_package' => $pricing->package_name,
                    'voucher_quantity' => $quantity,
                    'voucher_profile' => $pricing->package_name,
                    'voucher_data' => $status === 'completed' ? json_encode($voucherData) : null,
                    'status' => $status,
                    'payment_gateway' => rand(0, 1) ? 'midtrans' : 'xendit',
                    'payment_transaction_id' => 'TRX-' . Str::upper(Str::random(12)),
                    'payment_url' => $status === 'pending' ? 'https://payment.example.com/' . Str::random(10) : null,
                    'completed_at' => $status === 'completed' ? now()->subDays(rand(0, 30)) : null,
                    'created_at' => now()->subDays(rand(0, 30)),
                ]);
            }
        }
    }
}
