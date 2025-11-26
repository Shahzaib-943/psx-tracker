<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use App\Constants\AppConstant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks before truncating the table
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate the 'permissions' table
        DB::table('permissions')->truncate();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        DB::table('permissions')->insert([
            [ 'name' => 'view users', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            [ 'name' => 'edit users', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            [ 'name' => 'delete users', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            [ 'name' => 'create users', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            [ 'name' => 'view roles', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            [ 'name' => 'edit roles', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            [ 'name' => 'delete roles', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            [ 'name' => 'create roles', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            [ 'name' => 'create portfolios', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            [ 'name' => 'view portfolios', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            [ 'name' => 'edit portfolios', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            [ 'name' => 'delete portfolios', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            [ 'name' => 'view system-settings', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            [ 'name' => 'edit system-settings', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            [ 'name' => 'delete system-settings', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            [ 'name' => 'create system-settings', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $adminRole = Role::firstOrCreate(['name' => AppConstant::ROLE_ADMIN]);
        $adminRole->givePermissionTo(Permission::all());
        Role::firstOrCreate(['name' => AppConstant::ROLE_USER]);
    }
}
