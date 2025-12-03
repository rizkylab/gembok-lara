<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\AgentMonthlyPayment;
use App\Models\Customer;
use App\Models\Invoice;
use Illuminate\Database\Seeder;

class AgentMonthlyPaymentSeeder extends Seeder
{
    public function run(): void
    {
        $agents = Agent::all();
        $customers = Customer::all();
        $invoices = Invoice::where('status', 'paid')->get();
        
        if ($agents->isEmpty() || $customers->isEmpty() || $invoices->isEmpty()) {
            return;
        }

        $paymentMethods = ['cash', 'transfer', 'e-wallet'];

        foreach ($invoices->take(10) as $invoice) {
            AgentMonthlyPayment::create([
                'agent_id' => $agents->random()->id,
                'customer_id' => $invoice->customer_id,
                'invoice_id' => $invoice->id,
                'payment_amount' => $invoice->amount,
                'commission_amount' => $invoice->amount * 0.05, // 5% commission
                'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                'notes' => rand(0, 1) ? 'Pembayaran lancar' : null,
                'status' => 'completed',
                'paid_at' => $invoice->paid_date ?? now(),
            ]);
        }
    }
}
