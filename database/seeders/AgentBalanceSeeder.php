<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\AgentBalance;
use Illuminate\Database\Seeder;

class AgentBalanceSeeder extends Seeder
{
    public function run(): void
    {
        $agents = Agent::all();
        
        if ($agents->isEmpty()) {
            return;
        }

        foreach ($agents as $agent) {
            AgentBalance::create([
                'agent_id' => $agent->id,
                'balance' => rand(500000, 5000000),
                'last_updated' => now(),
            ]);
        }
    }
}
