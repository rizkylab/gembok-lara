<?php

namespace Database\Seeders;

use App\Models\VoucherOnlineSetting;
use Illuminate\Database\Seeder;

class VoucherOnlineSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'package_id' => 'PKG-1H',
                'profile' => '1 Hour',
                'enabled' => true,
                'duration' => 1,
                'duration_type' => 'hours',
            ],
            [
                'package_id' => 'PKG-3H',
                'profile' => '3 Hours',
                'enabled' => true,
                'duration' => 3,
                'duration_type' => 'hours',
            ],
            [
                'package_id' => 'PKG-1D',
                'profile' => '1 Day',
                'enabled' => true,
                'duration' => 24,
                'duration_type' => 'hours',
            ],
            [
                'package_id' => 'PKG-7D',
                'profile' => '7 Days',
                'enabled' => true,
                'duration' => 7,
                'duration_type' => 'days',
            ],
            [
                'package_id' => 'PKG-30D',
                'profile' => '30 Days',
                'enabled' => true,
                'duration' => 30,
                'duration_type' => 'days',
            ],
        ];

        foreach ($settings as $setting) {
            VoucherOnlineSetting::create($setting);
        }
    }
}
