<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Odp;
use App\Models\OnuDevice;
use Illuminate\Database\Seeder;

class OnuDeviceSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::where('status', 'active')->get();
        $odps = Odp::all();
        
        if ($customers->isEmpty() || $odps->isEmpty()) {
            return;
        }

        $models = ['Huawei HG8245H', 'ZTE F660', 'Fiberhome HG6243C', 'TP-Link TX-VG1530'];
        $statuses = ['online', 'offline', 'maintenance'];

        foreach ($customers->take(10) as $index => $customer) {
            OnuDevice::create([
                'name' => 'ONU-' . $customer->username,
                'serial_number' => 'SN' . strtoupper(substr(md5($customer->id), 0, 12)),
                'mac_address' => sprintf('%02X:%02X:%02X:%02X:%02X:%02X', 
                    rand(0, 255), rand(0, 255), rand(0, 255), 
                    rand(0, 255), rand(0, 255), rand(0, 255)
                ),
                'ip_address' => '192.168.1.' . (100 + $index),
                'status' => $statuses[array_rand($statuses)],
                'latitude' => -6.2 + (rand(-100, 100) / 1000),
                'longitude' => 106.8 + (rand(-100, 100) / 1000),
                'customer_id' => $customer->id,
                'odp_id' => $odps->random()->id,
                'ssid' => 'WiFi-' . $customer->username,
                'password' => 'pass' . rand(10000, 99999),
                'model' => $models[array_rand($models)],
                'firmware_version' => 'v' . rand(1, 3) . '.' . rand(0, 9) . '.' . rand(0, 20),
            ]);
        }
    }
}
