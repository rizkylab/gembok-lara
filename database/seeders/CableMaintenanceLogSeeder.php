<?php

namespace Database\Seeders;

use App\Models\CableMaintenanceLog;
use App\Models\CableRoute;
use App\Models\NetworkSegment;
use App\Models\Technician;
use Illuminate\Database\Seeder;

class CableMaintenanceLogSeeder extends Seeder
{
    public function run(): void
    {
        $cableRoutes = CableRoute::all();
        $networkSegments = NetworkSegment::all();
        $technicians = Technician::all();
        
        if ($technicians->isEmpty()) {
            return;
        }

        $maintenanceTypes = ['Repair', 'Inspection', 'Upgrade', 'Installation', 'Troubleshooting'];

        // Maintenance untuk cable routes
        foreach ($cableRoutes->take(5) as $route) {
            CableMaintenanceLog::create([
                'cable_route_id' => $route->id,
                'maintenance_type' => $maintenanceTypes[array_rand($maintenanceTypes)],
                'description' => 'Perbaikan kabel fiber optic yang putus',
                'performed_by' => $technicians->random()->id,
                'maintenance_date' => now()->subDays(rand(1, 30)),
                'duration_hours' => rand(1, 8) + (rand(0, 1) * 0.5),
                'cost' => rand(100000, 500000),
                'notes' => rand(0, 1) ? 'Selesai dengan baik' : null,
            ]);
        }

        // Maintenance untuk network segments
        foreach ($networkSegments->take(3) as $segment) {
            CableMaintenanceLog::create([
                'network_segment_id' => $segment->id,
                'maintenance_type' => $maintenanceTypes[array_rand($maintenanceTypes)],
                'description' => 'Inspeksi rutin backbone network',
                'performed_by' => $technicians->random()->id,
                'maintenance_date' => now()->subDays(rand(1, 30)),
                'duration_hours' => rand(2, 6) + (rand(0, 1) * 0.5),
                'cost' => rand(200000, 1000000),
                'notes' => rand(0, 1) ? 'Tidak ada masalah ditemukan' : null,
            ]);
        }
    }
}
