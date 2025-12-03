<?php

namespace Database\Seeders;

use App\Models\CableRoute;
use App\Models\Customer;
use App\Models\Odp;
use Illuminate\Database\Seeder;

class CableRouteSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::where('status', 'active')->get();
        $odps = Odp::all();
        
        if ($customers->isEmpty() || $odps->isEmpty()) {
            return;
        }

        $cableTypes = ['Fiber Optic', 'Drop Cable', 'Patch Cord'];
        $statuses = ['connected', 'disconnected', 'maintenance'];

        foreach ($customers as $customer) {
            CableRoute::create([
                'customer_id' => $customer->id,
                'odp_id' => $odps->random()->id,
                'cable_length' => rand(50, 500) / 10, // 5.0 - 50.0 meter
                'cable_type' => $cableTypes[array_rand($cableTypes)],
                'installation_date' => $customer->join_date,
                'status' => $statuses[array_rand($statuses)],
                'port_number' => rand(1, 64),
                'notes' => rand(0, 1) ? 'Instalasi normal' : null,
            ]);
        }
    }
}
