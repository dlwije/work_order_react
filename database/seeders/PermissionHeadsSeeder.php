<?php

namespace Database\Seeders;

use App\Models\PermissionHead;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionHeadsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        PermissionHead::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $permissionHead = [
            'Labors task',
            'Cus Deposit',
            'Cus Payment',
            'Invoice',
            'Common',
            'Customer',
            'Workorder',
            'Labortask',
            'Joborder',
            'Joborder Approve',
            'Estimate',
            'Task Category',
            'Task Source',
            'Task Type',
            'Vehicle Type',
            'Role',
            'Users',
            'Company',
            'Employee',
            'Item',
            'Uofm',
            'Vehicle',
            'Service Package',
            'Warranty Worder',
        ];

        foreach ($permissionHead as $permission) {
            PermissionHead::create(['permission_title' => $permission]);
        }
    }
}
