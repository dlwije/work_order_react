<?php

namespace Database\Seeders;

use App\Models\CategorySMA;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SMACategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        CategorySMA::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        CategorySMA::insert(['code' => 'C00', 'name' => 'DEFAULT', 'parent_id' => NULL, 'slug' => NULL, 'description' => NULL]);
        CategorySMA::insert(['code' => 'C01', 'name' => 'TOYOTA', 'parent_id' => NULL, 'slug' => NULL, 'description' => NULL]);
        CategorySMA::insert(['code' => 'C01-1', 'name' => '10W30', 'parent_id' => 2, 'slug' => NULL, 'description' => NULL]);
        CategorySMA::insert(['code' => 'C01-2', 'name' => '5W30', 'parent_id' => 2, 'slug' => NULL, 'description' => NULL]);
        CategorySMA::insert(['code' => 'C01-3', 'name' => '0W20', 'parent_id' => 2, 'slug' => NULL, 'description' => NULL]);
        CategorySMA::insert(['code' => 'C01-4', 'name' => '15W40', 'parent_id' => 2, 'slug' => NULL, 'description' => NULL]);
        CategorySMA::insert(['code' => 'C01-5', 'name' => '85W90', 'parent_id' => 2, 'slug' => NULL, 'description' => NULL]);
        CategorySMA::insert(['code' => 'C01-6', 'name' => '75W90', 'parent_id' => 2, 'slug' => NULL, 'description' => NULL]);
        CategorySMA::insert(['code' => 'C01-7', 'name' => 'D-II', 'parent_id' => 2, 'slug' => NULL, 'description' => NULL]);
        CategorySMA::insert(['code' => 'C01-8', 'name' => 'T-IV', 'parent_id' => 2, 'slug' => NULL, 'description' => NULL]);
        CategorySMA::insert(['code' => 'C01-9', 'name' => 'Fuel Filters', 'parent_id' => 2, 'slug' => NULL, 'description' => NULL]);
        CategorySMA::insert(['code' => 'C01-10', 'name' => 'Spark Plugs', 'parent_id' => 2, 'slug' => NULL, 'description' => NULL]);
        CategorySMA::insert(['code' => 'C01-11', 'name' => 'Coolant', 'parent_id' => 2, 'slug' => NULL, 'description' => NULL]);
        CategorySMA::insert(['code' => 'C02', 'name' => 'VALVOLINE', 'parent_id' => NULL, 'slug' => NULL, 'description' => NULL]);
        CategorySMA::insert(['code' => 'C03', 'name' => 'WURTH', 'parent_id' => NULL, 'slug' => NULL, 'description' => NULL]);
        CategorySMA::insert(['code' => 'C04', 'name' => '3M', 'parent_id' => NULL, 'slug' => NULL, 'description' => NULL]);
        CategorySMA::insert(['code' => 'C05', 'name' => 'SUZUKI', 'parent_id' => NULL, 'slug' => NULL, 'description' => NULL]);
        CategorySMA::insert(['code' => 'C06', 'name' => 'SHELL', 'parent_id' => NULL, 'slug' => NULL, 'description' => NULL]);
        CategorySMA::insert(['code' => 'C07', 'name' => 'SEIKEN', 'parent_id' => NULL, 'slug' => NULL, 'description' => NULL]);
        CategorySMA::insert(['code' => 'C08', 'name' => 'SAKURA', 'parent_id' => NULL, 'slug' => NULL, 'description' => NULL]);
        CategorySMA::insert(['code' => 'C09', 'name' => 'NISSAN', 'parent_id' => NULL, 'slug' => NULL, 'description' => NULL]);
    }
}
