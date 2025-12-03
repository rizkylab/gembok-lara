<?php

namespace Database\Seeders;

use App\Models\VoucherGenerationSetting;
use Illuminate\Database\Seeder;

class VoucherGenerationSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'setting_key' => 'prefix',
                'setting_value' => 'VCH',
            ],
            [
                'setting_key' => 'length',
                'setting_value' => '8',
            ],
            [
                'setting_key' => 'format',
                'setting_value' => 'alphanumeric',
            ],
            [
                'setting_key' => 'auto_generate',
                'setting_value' => 'true',
            ],
            [
                'setting_key' => 'expiry_days',
                'setting_value' => '30',
            ],
        ];

        foreach ($settings as $setting) {
            VoucherGenerationSetting::create($setting);
        }
    }
}
