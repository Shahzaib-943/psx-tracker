<?php

namespace Database\Seeders;

use App\Models\FinanceType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FinanceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [["name" => 'income'], ["name" => "expense"]];
        foreach ($types as $type) {
            FinanceType::create($type);
        }
    }
}
