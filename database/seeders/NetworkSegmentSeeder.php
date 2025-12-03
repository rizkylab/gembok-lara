<?php

namespace Database\Seeders;

use App\Models\NetworkSegment;
use App\Models\Odp;
use Illuminate\Database\Seeder;

class NetworkSegmentSeeder extends Seeder
{
    public function run(): void
    {
        $odps = Odp::all();
        
        if ($odps->count() < 2) {
            return;
        }

        $segmentTypes = ['Backbone', 'Distribution', 'Feeder'];
        $statuses = ['active', 'inactive', 'maintenance'];

        for ($i = 0; $i < min(5, $odps->count() - 1); $i++) {
            NetworkSegment::create([
                'name' => 'Segment-' . chr(65 + $i),
                'start_odp_id' => $odps[$i]->id,
                'end_odp_id' => $odps[$i + 1]->id,
                'segment_type' => $segmentTypes[array_rand($segmentTypes)],
                'cable_length' => rand(100, 5000) / 10, // 10.0 - 500.0 meter
                'status' => $statuses[array_rand($statuses)],
                'installation_date' => now()->subMonths(rand(1, 12)),
                'notes' => rand(0, 1) ? 'Segment utama' : null,
            ]);
        }
    }
}
