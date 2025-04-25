<?php

namespace Database\Seeders;


use App\Models\User;
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
            'email' => User::EMAIL_ADMIN,
            'password' => 'admin123'
        ]);

        if($user) {
            $user->assignRole(AppConstant::ROLE_ADMIN);
        }
    }
}
