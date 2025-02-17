<?php

namespace Database\Seeders;

use App\Models\Stock;
use App\Models\Sector;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SectorAndStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $response = Http::get('https://dps.psx.com.pk/symbols');
        $data = $response->json();
        $types = array_filter(array_unique(array_column($data, 'sectorName')));
        $sectorIds = [];
        foreach ($types as $type) {
            $sector = Sector::firstOrCreate(['name' => $type, 'slug' => Str::slug($type)]);
            $sectorIds[$type] = $sector->id;
        }

        foreach ($data as $stock) {
            if (!empty($stock['symbol']) && !empty($stock['sectorName']) && isset($sectorIds[$stock['sectorName']])) {
                Stock::firstOrCreate([
                    'sector_id' => $sectorIds[$stock['sectorName']],
                    'symbol' => $stock['symbol'],
                    'slug' => Str::slug($stock['symbol']),
                    'name' => $stock['name'],
                ]);
            }
        }
    }
}