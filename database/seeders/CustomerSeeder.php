<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Package;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $packages = Package::all();
        
        if ($packages->isEmpty()) {
            return;
        }

        $customers = [
            [
                'username' => 'ahmad123',
                'pppoe_username' => 'pppoe-ahmad',
                'pppoe_password' => 'ahmad123',
                'name' => 'Ahmad Wijaya',
                'phone' => '081299887766',
                'email' => 'ahmad@gmail.com',
                'address' => 'Jl. Merdeka No. 45, Jakarta Pusat',
                'package_id' => $packages->first()->id,
                'status' => 'active',
                'join_date' => now()->subMonths(6),
            ],
            [
                'username' => 'bambang_net',
                'pppoe_username' => 'pppoe-bambang',
                'pppoe_password' => 'bambang123',
                'name' => 'Bambang Santoso',
                'phone' => '081255443322',
                'email' => 'bambang@yahoo.com',
                'address' => 'Jl. Sudirman Kav. 10, Jakarta Selatan',
                'package_id' => $packages->last()->id,
                'status' => 'active',
                'join_date' => now()->subMonths(3),
            ],
            [
                'username' => 'siti_user',
                'pppoe_username' => 'pppoe-siti',
                'pppoe_password' => 'siti123',
                'name' => 'Siti Nurhaliza',
                'phone' => '081234567890',
                'email' => 'siti@gmail.com',
                'address' => 'Jl. Gatot Subroto No. 88, Jakarta Barat',
                'package_id' => $packages->random()->id,
                'status' => 'active',
                'join_date' => now()->subMonths(4),
            ],
            [
                'username' => 'budi_net',
                'pppoe_username' => 'pppoe-budi',
                'pppoe_password' => 'budi123',
                'name' => 'Budi Hartono',
                'phone' => '081298765432',
                'email' => 'budi@hotmail.com',
                'address' => 'Jl. Thamrin No. 12, Jakarta Pusat',
                'package_id' => $packages->random()->id,
                'status' => 'suspended',
                'join_date' => now()->subMonths(2),
            ],
            [
                'username' => 'dewi123',
                'pppoe_username' => 'pppoe-dewi',
                'pppoe_password' => 'dewi123',
                'name' => 'Dewi Lestari',
                'phone' => '081276543210',
                'email' => 'dewi@gmail.com',
                'address' => 'Jl. Rasuna Said No. 5, Jakarta Selatan',
                'package_id' => $packages->random()->id,
                'status' => 'active',
                'join_date' => now()->subMonth(),
            ],
            [
                'username' => 'rudi_net',
                'pppoe_username' => 'pppoe-rudi',
                'pppoe_password' => 'rudi123',
                'name' => 'Rudi Hermawan',
                'phone' => '081388776655',
                'email' => 'rudi@gmail.com',
                'address' => 'Jl. Kebon Jeruk No. 22, Jakarta Barat',
                'package_id' => $packages->random()->id,
                'status' => 'active',
                'join_date' => now()->subMonths(5),
            ],
            [
                'username' => 'maya_user',
                'pppoe_username' => 'pppoe-maya',
                'pppoe_password' => 'maya123',
                'name' => 'Maya Sari',
                'phone' => '081299112233',
                'email' => 'maya@yahoo.com',
                'address' => 'Jl. Kemang Raya No. 15, Jakarta Selatan',
                'package_id' => $packages->random()->id,
                'status' => 'active',
                'join_date' => now()->subMonths(2),
            ],
            [
                'username' => 'eko_net',
                'pppoe_username' => 'pppoe-eko',
                'pppoe_password' => 'eko123',
                'name' => 'Eko Prasetyo',
                'phone' => '081355667788',
                'email' => 'eko@gmail.com',
                'address' => 'Jl. Pluit Raya No. 8, Jakarta Utara',
                'package_id' => $packages->random()->id,
                'status' => 'active',
                'join_date' => now()->subWeeks(3),
            ],
            [
                'username' => 'rina_user',
                'pppoe_username' => 'pppoe-rina',
                'pppoe_password' => 'rina123',
                'name' => 'Rina Wulandari',
                'phone' => '081244556677',
                'email' => 'rina@hotmail.com',
                'address' => 'Jl. Cikini Raya No. 33, Jakarta Pusat',
                'package_id' => $packages->random()->id,
                'status' => 'inactive',
                'join_date' => now()->subMonths(8),
            ],
            [
                'username' => 'agus_net',
                'pppoe_username' => 'pppoe-agus',
                'pppoe_password' => 'agus123',
                'name' => 'Agus Setiawan',
                'phone' => '081377889900',
                'email' => 'agus@gmail.com',
                'address' => 'Jl. Kelapa Gading No. 50, Jakarta Utara',
                'package_id' => $packages->random()->id,
                'status' => 'active',
                'join_date' => now()->subWeeks(2),
            ],
        ];

        foreach ($customers as $customer) {
            Customer::updateOrCreate(
                ['username' => $customer['username']],
                $customer
            );
        }
    }
}
