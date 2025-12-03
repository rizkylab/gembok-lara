<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\AgentPayment;
use App\Models\Customer;
use App\Models\Invoice;
use Illuminate\Database\Seeder;

class AgentPaymentSeeder extends Seeder
{
    public function run(): void
    {
        $agents = Agent::all();
        $invoices = Invoice::where('status', 'paid')->get();
        
        if ($agents->isEmpty() || $invoices->isEmpty()) {
            return;
        }

        $paymentMethods = ['cash', 'transfer', 'e-wallet', 'qris'];

        foreach ($invoices->take(8) as $invoice) {
            AgentPayment::create([
                'agent_id' => $agents->random()->id,
                'customer_id' => $invoice->customer_id,
                'invoice_id' => $invoice->id,
                'amount' => $invoice->amount,
                'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                'notes' => rand(0, 1) ? 'Pembayaran melalui agent' : null,
                'status' => 'completed',
                'paid_at' => $invoice->paid_date ?? now(),
            ]);
        }
    }
}
