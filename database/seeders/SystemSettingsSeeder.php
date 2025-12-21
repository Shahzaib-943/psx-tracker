<?php

namespace Database\Seeders;

use App\Models\SystemSettings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SystemSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'market_opening_time',
                'value' => '09:00',
            ],
            [
                'key' => 'market_closing_time',
                'value' => '17:00',
            ]
        ];
        foreach ($settings as $setting) {
            SystemSettings::create($setting);
        }
    }
}
