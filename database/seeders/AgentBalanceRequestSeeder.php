<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\AgentBalanceRequest;
use Illuminate\Database\Seeder;

class AgentBalanceRequestSeeder extends Seeder
{
    public function run(): void
    {
        $agents = Agent::all();
        
        if ($agents->isEmpty()) {
            return;
        }

        $statuses = ['pending', 'approved', 'rejected'];

        foreach ($agents->take(3) as $agent) {
            for ($i = 0; $i < rand(1, 3); $i++) {
                $status = $statuses[array_rand($statuses)];
                
                AgentBalanceRequest::create([
                    'agent_id' => $agent->id,
                    'amount' => rand(100000, 1000000),
                    'status' => $status,
                    'admin_notes' => $status === 'rejected' ? 'Dokumen tidak lengkap' : null,
                    'requested_at' => now()->subDays(rand(1, 15)),
                    'processed_at' => $status !== 'pending' ? now()->subDays(rand(0, 10)) : null,
                    'processed_by' => $status !== 'pending' ? 1 : null,
                ]);
            }
        }
    }
}
