<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SMAOrderRefTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('sma_order_ref')->truncate();

        DB::table('sma_order_ref')->insert([
            'ref_id' =>1,
            'date' => '2015-03-01',
            'so' => 1,
            'qu' => 1,
            'po' => 1,
            'to' => 1,
            'pos' => 1,
            'do' => 1,
            'pay' => 1,
            're' => 1,
            'rep' => 1,
            'ex' => 1,
            'ppay' => 1,
            'qa' => 1,
        ]);

    }
}
