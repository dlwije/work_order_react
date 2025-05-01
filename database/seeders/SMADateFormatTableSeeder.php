<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SMADateFormatTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('sma_date_format')->truncate();

        DB::table('sma_date_format')->insert(['id' => 1,'js' => 'mm-dd-yyyy','php' => 'm-d-Y','sql' => '%m-%d-%Y',]);
        DB::table('sma_date_format')->insert(['id' => 2,'js' => 'mm/dd/yyyy','php' => 'm/d/Y','sql' => '%m/%d/%Y',]);
        DB::table('sma_date_format')->insert(['id' => 3,'js' => 'mm.dd.yyyy','php' => 'm.d.Y','sql' => '%m.%d.%Y',]);
        DB::table('sma_date_format')->insert(['id' => 4,'js' => 'dd-mm-yyyy','php' => 'd-m-Y','sql' => '%d-%m-%Y',]);
        DB::table('sma_date_format')->insert(['id' => 5,'js' => 'dd/mm/yyyy','php' => 'd/m/Y','sql' => '%d/%m/%Y',]);
        DB::table('sma_date_format')->insert(['id' => 6,'js' => 'dd.mm.yyyy','php' => 'd.m.Y','sql' => '%d.%m.%Y',]);
    }
}
