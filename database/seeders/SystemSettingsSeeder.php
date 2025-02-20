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
        SystemSettings::create([
            'market_open_time' => '09:15',
            'market_close_time' => '17:00',
        ]);
    }
}
