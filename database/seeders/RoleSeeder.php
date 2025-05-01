<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            Role::truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $permission = Permission::all();

        $roleAdmin = Role::create(['name' => 'System Admin']);
        Role::create(['name' => 'Service Writer']);
        Role::create(['name' => 'Service Manager']);
        Role::create(['name' => 'Store Manager']);
        Role::create(['name' => 'Warranty Manager']);
        Role::create(['name' => 'Customer']);
        Role::create(['name' => 'Supplier']);
        Role::create(['name' => 'Cashier']);
        Role::create(['name' => 'Technician']);

        $roleAdmin->syncPermissions($permission);
        }catch (\Exception $e){
            Log::error($e);
        }
    }
}
