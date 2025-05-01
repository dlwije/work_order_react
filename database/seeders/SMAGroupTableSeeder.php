<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SMAGroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('sma_groups')->truncate();
        DB::table('sma_groups')->insert(['name' => 'owner','description' => 'Owner']);
        DB::table('sma_groups')->insert(['name' => 'admin','description' => 'Administrator']);
        DB::table('sma_groups')->insert(['name' => 'customer','description' => 'Customer']);
        DB::table('sma_groups')->insert(['name' => 'supplier','description' => 'Supplier']);
        DB::table('sma_groups')->insert(['name' => 'sales','description' => 'Sales Staff']);

        DB::table('sma_groups')->insertGetId(['name' => 'system_admin', 'description' => '-']);
        DB::table('sma_groups')->insertGetId(['name' => 'service_writer', 'description' => '-']);
        DB::table('sma_groups')->insertGetId(['name' => 'service_manager', 'description' => '-']);
        DB::table('sma_groups')->insertGetId(['name' => 'store_manager', 'description' => '-']);
        DB::table('sma_groups')->insertGetId(['name' => 'warranty_manager', 'description' => '-']);
        DB::table('sma_groups')->insertGetId(['name' => 'cashier', 'description' => '-']);
        DB::table('sma_groups')->insertGetId(['name' => 'technician', 'description' => '-']);



    }
}
