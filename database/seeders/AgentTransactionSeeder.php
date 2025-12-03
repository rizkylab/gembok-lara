<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\AgentTransaction;
use Illuminate\Database\Seeder;

class AgentTransactionSeeder extends Seeder
{
    public function run(): void
    {
        $agents = Agent::all();
        
        if ($agents->isEmpty()) {
            return;
        }

        $transactionTypes = ['topup', 'withdrawal', 'commission', 'payment', 'refund'];
        $statuses = ['completed', 'pending', 'failed'];

        foreach ($agents as $agent) {
            // Buat beberapa transaksi untuk setiap agent
            for ($i = 0; $i < rand(3, 8); $i++) {
                AgentTransaction::create([
                    'agent_id' => $agent->id,
                    'transaction_type' => $transactionTypes[array_rand($transactionTypes)],
                    'amount' => rand(50000, 1000000),
                    'description' => 'Transaksi ' . ucfirst($transactionTypes[array_rand($transactionTypes)]),
                    'reference_id' => 'TRX-' . strtoupper(substr(md5(time() . $i), 0, 10)),
                    'status' => $statuses[array_rand($statuses)],
                    'created_at' => now()->subDays(rand(1, 30)),
                ]);
            }
        }
    }
}
