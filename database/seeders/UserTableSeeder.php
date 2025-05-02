<?php

namespace Database\Seeders;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('sma_users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $sysAdminRole = Role::where('name','System Admin')->first();
        $servWriterRole = Role::where('name','Service Writer')->first();
        $servManRole = Role::where('name','Service Manager')->first();
        $storeManaRole = Role::where('name','Store Manager')->first();
        $warrManRole = Role::where('name','Warranty Manager')->first();
        $customerRole = Role::where('name','Customer')->first();
        $supplierRole = Role::where('name','Supplier')->first();
        $cashierRole = Role::where('name','Cashier')->first();
        $techRole = Role::where('name','Technician')->first();

        $sysAdmin_name = 'SysAdmin'; $sysAdmin_email = 'sysadmin@test.com';
        $sysAdmin_password = '12345678'; $sysAdmin_first_name = 'System';
        $sysAdmin_last_name = 'Users'; $sysAdmin_user_name = 'System Admin';
        $sysAdmin_ip = '127.0.0.1'; $sysAdmin_is_active = 1;

        $wo_data = [
            'name' => $sysAdmin_name,
            'email' => $sysAdmin_email,
            'password' => Hash::make($sysAdmin_password),
            'first_name' => $sysAdmin_first_name,
            'last_name' => $sysAdmin_last_name,
            'user_name' => $sysAdmin_user_name,
            'registration_no' => 'SYS-000001',
            'mobile' => '+94710153734',
            'ip' => $sysAdmin_ip,
            'is_active' => $sysAdmin_is_active,
            'approve_status' => 1,
            ];
        $sysAdmin = User::create($wo_data);
        $pos_admin_user = [
            'username'       => $sysAdmin_user_name,
            'password'       => (new Controller())->hashPosPassword($sysAdmin_password),
            'email'          => $sysAdmin_email,
            'active'         => $sysAdmin_is_active,
            'first_name'     => $sysAdmin_first_name,
            'last_name'      => $sysAdmin_last_name,
            'company'        => $sysAdmin_first_name.' '.$sysAdmin_last_name,
            'phone'          => NULL, 'gender'         => NULL,
            'group_id'       => 1,
            'biller_id'      => NULL, 'warehouse_id'   => NULL,
            'view_right'     => '0', 'edit_right'      => '0', 'allow_discount' => '0',
            'ip_address'     => $sysAdmin_ip, 'created_on'     => time(), 'last_login'     => time(),
            'wo_user_id'     => $sysAdmin->id
        ];
        DB::table('sma_users')->insertGetId($pos_admin_user);

        $serv_writ_name = 'Al mansouri'; $serv_writ_email = 'servwrit@test.com';
        $serv_writ_password = '12345678'; $serv_writ_first_name = 'Al';
        $serv_writ_last_name = 'mansouri'; $serv_writ_user_name = 'Mansouri SWrit';
        $serv_writ_ip = '127.0.0.1'; $serv_writ_is_active = 1;
        $serv_writ = User::create([
            'name' => $serv_writ_name,
            'email' => $serv_writ_email,
            'password' => Hash::make($serv_writ_password),
            'first_name' => $serv_writ_first_name,
            'last_name' => $serv_writ_last_name,
            'user_name' => $serv_writ_user_name,
            'registration_no' => 'SYS-000002',
            'mobile' => '+94710153734',
            'ip' => $serv_writ_ip,
            'is_active' => $serv_writ_is_active,
            'approve_status' => 1,
        ]);
        DB::table('sma_users')->insertGetId([
            'username'       => $serv_writ_user_name,
            'password'       => (new Controller())->hashPosPassword($serv_writ_password),
            'email'          => $serv_writ_email,
            'active'         => $serv_writ_is_active,
            'first_name'     => $serv_writ_first_name,
            'last_name'      => $serv_writ_last_name,
            'company'        => $serv_writ_first_name.' '.$serv_writ_last_name,
            'phone'          => NULL, 'gender'         => NULL,
            'group_id'       => 7,
            'biller_id'      => NULL, 'warehouse_id'   => NULL,
            'view_right'     => '0', 'edit_right'      => '0', 'allow_discount' => '0',
            'ip_address'     => $serv_writ_ip, 'created_on'     => time(), 'last_login'     => time(),
            'wo_user_id'     => $serv_writ->id
        ]);

        $serv_mana_name = 'Al mazrouei'; $serv_mana_email = 'servmana@test.com';
        $serv_mana_password = '12345678'; $serv_mana_first_name = 'Al';
        $serv_mana_last_name = 'mazrouei'; $serv_mana_user_name = 'Mazrouei SMana';
        $serv_mana_ip = '127.0.0.1'; $serv_mana_is_active = 1;
        $serv_mana = User::create([
            'name' => $serv_mana_name,
            'email' => $serv_mana_email,
            'password' => Hash::make($serv_mana_password),
            'first_name' => $serv_mana_first_name,
            'last_name' => $serv_mana_last_name,
            'user_name' => $serv_mana_user_name,
            'registration_no' => 'SYS-000003',
            'mobile' => '+94710153734',
            'ip' => $serv_mana_ip,
            'is_active' => $serv_mana_is_active,
            'approve_status' => 1,
        ]);
        DB::table('sma_users')->insertGetId([
            'username'       => $serv_mana_user_name,
            'password'       => (new Controller())->hashPosPassword($serv_mana_password),
            'email'          => $serv_mana_email,
            'active'         => $serv_mana_is_active,
            'first_name'     => $serv_mana_first_name,
            'last_name'      => $serv_mana_last_name,
            'company'        => $serv_mana_first_name.' '.$serv_mana_last_name,
            'phone'          => NULL, 'gender'         => NULL,
            'group_id'       => 8,
            'biller_id'      => NULL, 'warehouse_id'   => NULL,
            'view_right'     => '0', 'edit_right'      => '0', 'allow_discount' => '0',
            'ip_address'     => $serv_mana_ip, 'created_on'     => time(), 'last_login'     => time(),
            'wo_user_id'     => $serv_mana->id
        ]);

        $stor_mana_name = 'El sewedy'; $stor_mana_email = 'stormana@test.com';
        $stor_mana_password = '12345678'; $stor_mana_first_name = 'El';
        $stor_mana_last_name = 'sewedy'; $stor_mana_user_name = 'Sewedy SMana';
        $stor_mana_ip = '127.0.0.1'; $stor_mana_is_active = 1;
        $stor_mana = User::create([
            'name' => $stor_mana_name,
            'email' => $stor_mana_email,
            'password' => Hash::make($stor_mana_password),
            'first_name' => $stor_mana_first_name,
            'last_name' => $stor_mana_last_name,
            'user_name' => $stor_mana_user_name,
            'registration_no' => 'SYS-000004',
            'mobile' => '+94710153734',
            'ip' => $stor_mana_ip,
            'is_active' => $stor_mana_is_active,
            'approve_status' => 1,
        ]);
        DB::table('sma_users')->insertGetId([
            'username'       => $stor_mana_user_name,
            'password'       => (new Controller())->hashPosPassword($stor_mana_password),
            'email'          => $stor_mana_email,
            'active'         => $stor_mana_is_active,
            'first_name'     => $stor_mana_first_name,
            'last_name'      => $stor_mana_last_name,
            'company'        => $stor_mana_first_name.' '.$stor_mana_last_name,
            'phone'          => NULL, 'gender'         => NULL,
            'group_id'       => 9,
            'biller_id'      => NULL, 'warehouse_id'   => NULL,
            'view_right'     => '0', 'edit_right'      => '0', 'allow_discount' => '0',
            'ip_address'     => $stor_mana_ip, 'created_on'     => time(), 'last_login'     => time(),
            'wo_user_id'     => $stor_mana->id
        ]);

        $warr_mana_name = 'Al qubaisi'; $warr_mana_email = 'warrmana@test.com';
        $warr_mana_password = '12345678'; $warr_mana_first_name = 'Al';
        $warr_mana_last_name = 'qubaisi'; $warr_mana_user_name = 'qubaisi WMana';
        $warr_mana_ip = '127.0.0.1'; $warr_mana_is_active = 1;
        $warr_mana = User::create([
            'name' => $warr_mana_name,
            'email' => $warr_mana_email,
            'password' => Hash::make($warr_mana_password),
            'first_name' => $warr_mana_first_name,
            'last_name' => $warr_mana_last_name,
            'user_name' => $warr_mana_user_name,
            'registration_no' => 'SYS-000005',
            'mobile' => '+94710153734',
            'ip' => $warr_mana_ip,
            'is_active' => $warr_mana_is_active,
            'approve_status' => 1,
        ]);
        DB::table('sma_users')->insertGetId([
            'username'       => $warr_mana_user_name,
            'password'       => (new Controller())->hashPosPassword($warr_mana_password),
            'email'          => $warr_mana_email,
            'active'         => $warr_mana_is_active,
            'first_name'     => $warr_mana_first_name,
            'last_name'      => $warr_mana_last_name,
            'company'        => $warr_mana_first_name.' '.$warr_mana_last_name,
            'phone'          => NULL, 'gender'         => NULL,
            'group_id'       => 10,
            'biller_id'      => NULL, 'warehouse_id'   => NULL,
            'view_right'     => '0', 'edit_right'      => '0', 'allow_discount' => '0',
            'ip_address'     => $warr_mana_ip, 'created_on'     => time(), 'last_login'     => time(),
            'wo_user_id'     => $warr_mana->id
        ]);

        $cust_name = 'Dinesh Lakmal'; $cust_email = 'dish@test.com';
        $cust_password = '12345678'; $cust_first_name = 'Dinesh';
        $cust_last_name = 'lakmal'; $cust_user_name = 'Lakmal Cust';
        $cust_ip = '127.0.0.1'; $cust_is_active = 1;
        $cust = User::create([
            'name' => $cust_name,
            'email' => $cust_email,
            'password' => Hash::make($cust_password),
            'first_name' => $cust_first_name,
            'last_name' => $cust_last_name,
            'user_name' => $cust_user_name,
            'registration_no' => 'SYS-000006',
            'mobile' => '+94710153734',
            'ip' => $cust_ip,
            'is_active' => $cust_is_active,
            'approve_status' => 1,
        ]);
        DB::table('sma_users')->insertGetId([
            'username'       => $cust_user_name,
            'password'       => (new Controller())->hashPosPassword($cust_password),
            'email'          => $cust_email,
            'active'         => $cust_is_active,
            'first_name'     => $cust_first_name,
            'last_name'      => $cust_last_name,
            'company'        => $cust_first_name.' '.$cust_last_name,
            'phone'          => NULL, 'gender'         => NULL,
            'group_id'       => 3,
            'biller_id'      => NULL, 'warehouse_id'   => NULL,
            'view_right'     => '0', 'edit_right'      => '0', 'allow_discount' => '0',
            'ip_address'     => $cust_ip, 'created_on'     => time(), 'last_login'     => time(),
            'wo_user_id'     => $cust->id
        ]);

        $supp_name = 'El hammady'; $supp_email = 'sup@test.com';
        $supp_password = '12345678'; $supp_first_name = 'El';
        $supp_last_name = 'hammady'; $supp_user_name = 'hammady Supp';
        $supp_ip = '127.0.0.1'; $supp_is_active = 1;
        $supp = User::create([
            'name' => $supp_name,
            'email' => $supp_email,
            'password' => Hash::make($supp_password),
            'first_name' => $supp_first_name,
            'last_name' => $supp_last_name,
            'user_name' => $supp_user_name,
            'registration_no' => 'SYS-000007',
            'mobile' => '+94710153734',
            'ip' => $supp_ip,
            'is_active' => $supp_is_active,
            'approve_status' => 1,
        ]);
        DB::table('sma_users')->insertGetId([
            'username'       => $supp_user_name,
            'password'       => (new Controller())->hashPosPassword($supp_password),
            'email'          => $supp_email,
            'active'         => $supp_is_active,
            'first_name'     => $supp_first_name,
            'last_name'      => $supp_last_name,
            'company'        => $supp_first_name.' '.$supp_last_name,
            'phone'          => NULL, 'gender'         => NULL,
            'group_id'       => 4,
            'biller_id'      => NULL, 'warehouse_id'   => NULL,
            'view_right'     => '0', 'edit_right'      => '0', 'allow_discount' => '0',
            'ip_address'     => $supp_ip, 'created_on'     => time(), 'last_login'     => time(),
            'wo_user_id'     => $supp->id
        ]);

        $cashi_name = 'Agnus Maria H'; $cashi_email = 'cashi@test.com';
        $cashi_password = '12345678'; $cashi_first_name = 'Agnus';
        $cashi_last_name = 'Maria H'; $cashi_user_name = 'Maria H Cashi';
        $cashi_ip = '127.0.0.1'; $cashi_is_active = 1;
        $cashi = User::create([
            'name' => $cashi_name,
            'email' => $cashi_email,
            'password' => Hash::make($cashi_password),
            'first_name' => $cashi_first_name,
            'last_name' => $cashi_last_name,
            'user_name' => $cashi_user_name,
            'registration_no' => 'SYS-000008',
            'mobile' => '+94710153734',
            'ip' => $cashi_ip,
            'is_active' => $cashi_is_active,
            'approve_status' => 1,
        ]);
        DB::table('sma_users')->insertGetId([
            'username'       => $cashi_user_name,
            'password'       => (new Controller())->hashPosPassword($cashi_password),
            'email'          => $cashi_email,
            'active'         => $cashi_is_active,
            'first_name'     => $cashi_first_name,
            'last_name'      => $cashi_last_name,
            'company'        => $cashi_first_name.' '.$cashi_last_name,
            'phone'          => NULL, 'gender'         => NULL,
            'group_id'       => 4,
            'biller_id'      => NULL, 'warehouse_id'   => NULL,
            'view_right'     => '0', 'edit_right'      => '0', 'allow_discount' => '0',
            'ip_address'     => $cashi_ip, 'created_on'     => time(), 'last_login'     => time(),
            'wo_user_id'     => $cashi->id
        ]);

        $tech_name = 'Reza ul karim rana'; $tech_email = 'tech@test.com';
        $tech_password = '12345678'; $tech_first_name = 'Reza ul';
        $tech_last_name = 'karim rana'; $tech_user_name = 'karim rana Tech';
        $tech_ip = '127.0.0.1'; $tech_is_active = 1;
        $tech = User::create([
            'name' => $tech_name,
            'email' => $tech_email,
            'password' => Hash::make($tech_password),
            'first_name' => $tech_first_name,
            'last_name' => $tech_last_name,
            'user_name' => $tech_user_name,
            'registration_no' => 'SYS-000009',
            'mobile' => '+94710153734',
            'ip' => $tech_ip,
            'is_active' => $tech_is_active,
            'approve_status' => 1,
        ]);
        DB::table('sma_users')->insertGetId([
            'username'       => $tech_user_name,
            'password'       => (new Controller())->hashPosPassword($tech_password),
            'email'          => $tech_email,
            'active'         => $tech_is_active,
            'first_name'     => $tech_first_name,
            'last_name'      => $tech_last_name,
            'company'        => $tech_first_name.' '.$tech_last_name,
            'phone'          => NULL, 'gender'         => NULL,
            'group_id'       => 4,
            'biller_id'      => NULL, 'warehouse_id'   => NULL,
            'view_right'     => '0', 'edit_right'      => '0', 'allow_discount' => '0',
            'ip_address'     => $tech_ip, 'created_on'     => time(), 'last_login'     => time(),
            'wo_user_id'     => $tech->id
        ]);

        $sysAdmin->assignRole($sysAdminRole);
        $serv_writ->assignRole($servWriterRole);
        $serv_mana->assignRole($servManRole);
        $stor_mana->assignRole($storeManaRole);
        $warr_mana->assignRole($warrManRole);
        $cust->assignRole($customerRole);
        $supp->assignRole($supplierRole);
        $cashi->assignRole($cashierRole);
        $tech->assignRole($techRole);
    }
}
