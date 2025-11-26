<?php

namespace Database\Seeders;


use App\Models\User;
use App\Constants\AppConstant;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Shahzaib',
            'email' => AppConstant::EMAIL_ADMIN,
            'password' => 'admin123',
            'public_id' => generatePublicId(User::class)
        ]);

        if($user) {
            $user->assignRole(AppConstant::ROLE_ADMIN);
        }
    }
}
