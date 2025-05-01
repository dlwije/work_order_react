<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SMAPriceGroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('sma_price_groups')->truncate();

        DB::table('sma_price_groups')->insert(['id' => 1,'name' => 'Default']);

    }
}
