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
                'name' => 'Dewi Lestari',
                'phone' => '081276543210',
                'email' => 'dewi@gmail.com',
                'address' => 'Jl. Rasuna Said No. 5, Jakarta Selatan',
                'package_id' => $packages->random()->id,
                'status' => 'active',
                'join_date' => now()->subMonth(),
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}
