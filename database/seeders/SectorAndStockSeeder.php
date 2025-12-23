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
        $response = Http::timeout(30)->get('https://dps.psx.com.pk/symbols');
        
        if (!$response->successful()) {
            $this->command->error("Failed to fetch symbols from API. Status: {$response->status()}");
            $this->command->error("Response body: " . $response->body());
            throw new \Exception("API request failed with status {$response->status()}");
        }
        
        $data = $response->json();
        
        if (!is_array($data) || empty($data)) {
            $this->command->error("Invalid or empty response from API.");
            $this->command->error("Response body: " . $response->body());
            throw new \Exception("API returned invalid data. Expected array, got: " . gettype($data));
        }
        
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