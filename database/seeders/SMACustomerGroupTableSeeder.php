<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SMACustomerGroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('sma_customer_groups')->truncate();

        DB::table('sma_customer_groups')->insert([
            'id' => 1,
            'name' => 'General',
            'percent' => 0,
            'discount' => NULL
        ]);

        DB::table('sma_customer_groups')->insert([
            'id' => 2,
            'name' => 'Reseller',
            'percent' => -5,
            'discount' => NULL
        ]);

        DB::table('sma_customer_groups')->insert([
            'id' => 3,
            'name' => 'Distributor',
            'percent' => -15,
            'discount' => NULL
        ]);

        DB::table('sma_customer_groups')->insert([
            'id' => 4,
            'name' => 'New Customer (+10)',
            'percent' => 10,
            'discount' => NULL
        ]);
    }
}
