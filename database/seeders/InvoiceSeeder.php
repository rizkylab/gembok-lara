<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Invoice;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::with('package')->get();
        
        if ($customers->isEmpty()) {
            return;
        }

        $invoiceNumber = 1;

        foreach ($customers as $customer) {
            if (!$customer->package) {
                continue;
            }

            // Invoice bulan lalu (paid)
            Invoice::create([
                'invoice_number' => 'INV-' . str_pad($invoiceNumber++, 6, '0', STR_PAD_LEFT),
                'customer_id' => $customer->id,
                'package_id' => $customer->package_id,
                'amount' => $customer->package->price,
                'tax_amount' => 0,
                'status' => 'paid',
                'invoice_type' => 'monthly',
                'due_date' => now()->subMonth()->addDays(7),
                'paid_date' => now()->subMonth()->addDays(3),
                'created_at' => now()->subMonth(),
            ]);

            // Invoice bulan ini
            if ($customer->status === 'active') {
                Invoice::create([
                    'invoice_number' => 'INV-' . str_pad($invoiceNumber++, 6, '0', STR_PAD_LEFT),
                    'customer_id' => $customer->id,
                    'package_id' => $customer->package_id,
                    'amount' => $customer->package->price,
                    'tax_amount' => 0,
                    'status' => rand(0, 1) ? 'paid' : 'unpaid',
                    'invoice_type' => 'monthly',
                    'due_date' => now()->addDays(7),
                    'paid_date' => rand(0, 1) ? now()->subDays(rand(1, 5)) : null,
                    'created_at' => now()->startOfMonth(),
                ]);
            } else {
                // Suspended customer - unpaid invoice
                Invoice::create([
                    'invoice_number' => 'INV-' . str_pad($invoiceNumber++, 6, '0', STR_PAD_LEFT),
                    'customer_id' => $customer->id,
                    'package_id' => $customer->package_id,
                    'amount' => $customer->package->price,
                    'tax_amount' => 0,
                    'status' => 'unpaid',
                    'invoice_type' => 'monthly',
                    'due_date' => now()->subDays(10),
                    'created_at' => now()->startOfMonth(),
                ]);
            }
        }
    }
}
