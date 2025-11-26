<?php

namespace Database\Seeders;

use App\Models\User;
use App\Constants\AppConstant;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'User',
            'email' => 'user@gmail.com',
            'password' => 'admin123',
            'public_id' => generatePublicId(User::class)
        ]);

        if($user) {
            $user->assignRole(AppConstant::ROLE_USER);
        }
    }
}
