<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Permission::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $permissions = [
            'labors-complete-list',
            'labors-assign-list',
            'cus-depo-list',
            'cus-pay-list',
            'invoice-list',
            'invoice-add',
            'invoice-edit',
            'invoice-delete',
            'invoice-view',
            'deposit',
            'customer-list',
            'customer-add',
            'customer-edit',
            'customer-delete',
            'customer-deposit-view',
            'customer-view',
            'workorder-list',
            'workorder-add',
            'workorder-edit',
            'workorder-delete',
            'workorder-view',
            'workorder-trans-est',
            'labortask-list',
            'labortask-add',
            'labortask-edit',
            'labortask-delete',
            'labortask-view',
            'labortask-excel-import',
            'joborder-list',
            'joborder-add',
            'joborder-edit',
            'joborder-delete',
            'joborder-view',
            'estimate-list',
            'estimate-add',
            'estimate-edit',
            'estimate-delete',
            'estimate-view',
            'estimate-trans-wo',
            'jo-cost-unapprove-list',
            'jo-cost-approve',
            'jo-work-unapprove-list',
            'jo-work-approve',
            'jo-approved-list',
            'task-category-list',
            'task-category-add',
            'task-category-edit',
            'task-category-delete',
            'task-category-view',
            'task-source-list',
            'task-source-add',
            'task-source-edit',
            'task-source-delete',
            'task-source-view',
            'task-type-list',
            'task-type-add',
            'task-type-edit',
            'task-type-delete',
            'task-type-view',
            'vehicle-type-list',
            'vehicle-type-add',
            'vehicle-type-edit',
            'vehicle-type-delete',
            'vehicle-type-view',
            'service_pkg-list',
            'service_pkg-add',
            'service_pkg-edit',
            'service_pkg-delete',
            'service_pkg-view',
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'user-management',
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            'batch-front-view',
            'wish-list-front-view',
            'wish-list-front-add',
            'wish-list-front-delete',
            'company-add',
            'company-edit',
            'company-view',
            'employee-edit',
            'employee-add',
            'employee-delete',
            'employee-view',
            'employee-list',
            'item-list',
            'item-add',
            'item-edit',
            'item-delete',
            'item-view',
            'uofm-list',
            'uofm-add',
            'uofm-edit',
            'uofm-delete',
            'uofm-show',
            'vehicle-list',
            'vehicle-add',
            'vehicle-edit',
            'vehicle-delete',
            'vehicle-view',
            'wworkorder-list',
            'wworkorder-add',
            'wworkorder-edit',
            'wworkorder-delete',
            'wworkorder-view',
        ];

        foreach ($permissions as $permission) {
            $ex_per = explode('-',$permission);
            if(isset($ex_per[0])){
                if($ex_per[0] == "labors")
                    Permission::create(['header_id' => 1,'name' => $permission]);
                else if($ex_per[0] == "cus")
                    Permission::create(['header_id' => 2,'name' => $permission]);
                else if($ex_per[0] == "invoice")
                    Permission::create(['header_id' => 4,'name' => $permission]);
                else if($ex_per[0] == "deposit")
                    Permission::create(['header_id' => 5,'name' => $permission]);
                else if($ex_per[0] == "customer")
                    Permission::create(['header_id' => 6,'name' => $permission]);
                else if($ex_per[0] == "workorder")
                    Permission::create(['header_id' => 7,'name' => $permission]);
                else if($ex_per[0] == "labortask")
                    Permission::create(['header_id' => 8,'name' => $permission]);
                else if($ex_per[0] == "joborder")
                    Permission::create(['header_id' => 9,'name' => $permission]);
                else if($ex_per[0] == "estimate")
                    Permission::create(['header_id' => 11,'name' => $permission]);
                else if($ex_per[0] == "jo")
                    Permission::create(['header_id' => 10,'name' => $permission]);
                else if($ex_per[0] == "task" && $ex_per[1] == "category")
                    Permission::create(['header_id' => 12,'name' => $permission]);
                else if($ex_per[0] == "task" && $ex_per[1] == "source")
                    Permission::create(['header_id' => 13,'name' => $permission]);
                else if($ex_per[0] == "task" && $ex_per[1] == "type")
                    Permission::create(['header_id' => 14,'name' => $permission]);
                else if($ex_per[0] == "vehicle" && $ex_per[1] == "type")
                    Permission::create(['header_id' => 15,'name' => $permission]);
                else if($ex_per[0] == "vehicle" && $ex_per[1] == "type")
                    Permission::create(['header_id' => 15,'name' => $permission]);
                else if($ex_per[0] == "service_pkg")
                    Permission::create(['header_id' => 23,'name' => $permission]);
                else if($ex_per[0] == "role")
                    Permission::create(['header_id' => 16,'name' => $permission]);
                else if($ex_per[0] == "user")
                    Permission::create(['header_id' => 17,'name' => $permission]);
                else if($ex_per[0] == "company")
                    Permission::create(['header_id' => 18,'name' => $permission]);
                else if($ex_per[0] == "employee")
                    Permission::create(['header_id' => 19,'name' => $permission]);
                else if($ex_per[0] == "item")
                    Permission::create(['header_id' => 20,'name' => $permission]);
                else if($ex_per[0] == "uofm")
                    Permission::create(['header_id' => 21,'name' => $permission]);
                else if($ex_per[0] == "vehicle")
                    Permission::create(['header_id' => 22,'name' => $permission]);
                else if($ex_per[0] == "wworkorder")
                    Permission::create(['header_id' => 24,'name' => $permission]);
            }
        }
    }
}
