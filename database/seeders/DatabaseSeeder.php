<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //disable foreign key check for this connection before running seeders
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->call(PermissionHeadsSeeder::class);
        $this->call(PermissionTableSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(FormSerialSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(StateSeeder::class);
        $this->call(CitySeeder::class);
        $this->call(VehicleTypeSeeder::class);
        $this->call(EmployeeSeeder::class);

        //SMA
        $this->call(SMAGroupTableSeeder::class);
        $this->call(SMAWarehouseTableSeeder::class);
        $this->call(SMACustomerGroupTableSeeder::class);
        $this->call(SMACompanyTableSeeder::class);
        $this->call(SMACurrencyTableSeeder::class);
        $this->call(SMADateFormatTableSeeder::class);
        $this->call(SMAOrderRefTableSeeder::class);
        $this->call(SMAPermissionTableSeeder::class);
        $this->call(SMAPosSettingTableSeeder::class);
        $this->call(SMASettingTableSeeder::class);
        $this->call(SMAPriceGroupTableSeeder::class);
        $this->call(SMAUserTableSeeder::class);
        $this->call(SMACategoriesTableSeeder::class);
        $this->call(SMAUnitsTableSeeder::class);

        // but I like to explicitly undo what I've done for clarity
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
