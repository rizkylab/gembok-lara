<?php

namespace Database\Seeders;

use App\Models\Odp;
use Illuminate\Database\Seeder;

class OdpSeeder extends Seeder
{
    public function run(): void
    {
        $odps = [
            [
                'name' => 'ODP-JKT-001',
                'code' => 'ODP-001',
                'address' => 'Tiang Listrik Depan Indomaret',
                'latitude' => -6.175110,
                'longitude' => 106.865036,
                'capacity' => 16,
                'used_ports' => 4,
                'status' => 'active',
                'installation_date' => now()->subMonths(6),
                'notes' => 'ODP utama area Jakarta Pusat',
            ],
            [
                'name' => 'ODP-JKT-002',
                'code' => 'ODP-002',
                'address' => 'Pertigaan Jl. Mawar',
                'latitude' => -6.176110,
                'longitude' => 106.866036,
                'capacity' => 8,
                'used_ports' => 6,
                'status' => 'active',
                'installation_date' => now()->subMonths(4),
                'notes' => 'ODP area perumahan',
            ],
            [
                'name' => 'ODP-JKT-003',
                'code' => 'ODP-003',
                'address' => 'Depan Masjid Al-Huda',
                'latitude' => -6.177110,
                'longitude' => 106.867036,
                'capacity' => 16,
                'used_ports' => 16,
                'status' => 'full',
                'installation_date' => now()->subMonths(8),
                'notes' => 'ODP penuh, perlu ekspansi',
            ],
            [
                'name' => 'ODP-JKT-004',
                'code' => 'ODP-004',
                'address' => 'Jl. Sudirman Kav. 25',
                'latitude' => -6.178110,
                'longitude' => 106.868036,
                'capacity' => 32,
                'used_ports' => 10,
                'status' => 'active',
                'installation_date' => now()->subMonths(3),
                'notes' => 'ODP baru untuk area perkantoran',
            ],
            [
                'name' => 'ODP-JKT-005',
                'code' => 'ODP-005',
                'address' => 'Jl. Gatot Subroto No. 100',
                'latitude' => -6.179110,
                'longitude' => 106.869036,
                'capacity' => 64,
                'used_ports' => 25,
                'status' => 'active',
                'installation_date' => now()->subMonths(2),
                'notes' => 'ODP kapasitas besar',
            ],
        ];

        foreach ($odps as $odp) {
            Odp::create($odp);
        }
    }
}
