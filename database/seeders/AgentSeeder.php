<?php

namespace Database\Seeders;

use App\Models\Agent;
use Illuminate\Database\Seeder;

class AgentSeeder extends Seeder
{
    public function run(): void
    {
        $agents = [
            [
                'username' => 'berkah',
                'name' => 'Warung Berkah',
                'phone' => '083134567890',
                'email' => 'berkah@agent.com',
                'address' => 'Jl. Kebon Jeruk No. 12',
                'status' => 'active',
                'commission_rate' => 5.00,
                'password' => bcrypt('password'),
            ],
            [
                'username' => 'jaya',
                'name' => 'Counter Pulsa Jaya',
                'phone' => '083134567891',
                'email' => 'jaya@agent.com',
                'address' => 'Jl. Raya Bogor KM 25',
                'status' => 'active',
                'commission_rate' => 5.00,
                'password' => bcrypt('password'),
            ],
            [
                'username' => 'makmur',
                'name' => 'Toko Makmur',
                'phone' => '083134567892',
                'email' => 'makmur@agent.com',
                'address' => 'Jl. Sudirman No. 88',
                'status' => 'active',
                'commission_rate' => 7.00,
                'password' => bcrypt('password'),
            ],
        ];

        foreach ($agents as $agent) {
            Agent::create($agent);
        }
    }
}
