<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SMACurrencyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('sma_currencies')->truncate();

        DB::table('sma_currencies')->insert([
            'id' => 1,
            'code' => 'USD',
            'name' => 'US Dollar',
            'rate' => 1.0000,
            'auto_update' => 0,
            'symbol' => NULL
        ]);

        DB::table('sma_currencies')->insert([
            'id' => 2,
            'code' => 'EUR',
            'name' => 'EURO',
            'rate' => 0.7340,
            'auto_update' => 0,
            'symbol' => NULL
        ]);
    }
}
