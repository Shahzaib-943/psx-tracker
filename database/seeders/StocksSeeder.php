<?php

namespace Database\Seeders;

use App\Models\Stock;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class StocksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $response = Http::get(config('app.api_base_url') . '/symbols');
        
        if (!$response->successful()) {
            $this->command->error("Failed to fetch symbols from API. Status: {$response->status()}");
            $this->command->error("Response body: " . $response->body());
            throw new \Exception("API request failed with status {$response->status()}");
        }
        
        $data = $response->json()['data'];
        
        if (!is_array($data) || empty($data)) {
            $this->command->error("Invalid or empty response from API.");
            $this->command->error("Response body: " . $response->body());
            throw new \Exception("API returned invalid data. Expected array, got: " . gettype($data));
        }

        foreach ($data as $stock) {
            Stock::firstOrCreate([
                'public_id' => generatePublicId(Stock::class),
                'symbol' => $stock,
            ]);
        }
    }
}