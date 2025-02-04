<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\FinanceType;
use Illuminate\Support\Str;
use App\Models\FinanceCategory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FinanceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $incomeTypeId = FinanceType::where("name", FinanceCategory::CATEGORY_INCOME)->first()->id;
        $expenseTypeId = FinanceType::where("name", FinanceCategory::CATEGORY_EXPENSE)->first()->id;
        $adminUserId = User::where('email', User::EMAIL_ADMIN)->first()->id;
        $categories = [
            [
                'name' => 'Travel',
                'finance_type_id' => $expenseTypeId,
                'color' => '#3498db',
                'is_common' => true,
                'user_id' => $adminUserId,
                'slug' => Str::slug('Travel'.'-'.$adminUserId)
            ],
            [
                'name' => 'Food',
                'finance_type_id' => $expenseTypeId,
                'color' => '#e74c3c',
                'is_common' => true,
                'user_id' => $adminUserId,
                'slug' => Str::slug('Food'.'-'.$adminUserId)
            ],
            [
                'name' => 'Grocery',
                'finance_type_id' => $expenseTypeId,
                'color' => '#2ecc71',
                'is_common' => true,
                'user_id' => $adminUserId,
                'slug' => Str::slug('Grocery'.'-'.$adminUserId)
            ],
            [
                'name' => 'Salary',
                'finance_type_id' => $incomeTypeId,
                'color' => '#f1c40f',
                'is_common' => true,
                'user_id' => $adminUserId,
                'slug' => Str::slug('Salary'.'-'.$adminUserId)
            ],
        ];

        foreach ($categories as $category) {
            FinanceCategory::create($category);
        }
    }
}
