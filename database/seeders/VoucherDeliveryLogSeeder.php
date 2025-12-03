<?php

namespace Database\Seeders;

use App\Models\VoucherDeliveryLog;
use App\Models\VoucherPurchase;
use Illuminate\Database\Seeder;

class VoucherDeliveryLogSeeder extends Seeder
{
    public function run(): void
    {
        $purchases = VoucherPurchase::where('status', 'completed')->get();
        
        if ($purchases->isEmpty()) {
            return;
        }

        $statuses = ['sent', 'failed', 'pending'];

        foreach ($purchases as $purchase) {
            VoucherDeliveryLog::create([
                'purchase_id' => $purchase->id,
                'phone' => $purchase->customer_phone,
                'status' => $statuses[array_rand($statuses)],
                'error_message' => rand(0, 1) ? null : 'Nomor tidak terdaftar',
            ]);
        }
    }
}
