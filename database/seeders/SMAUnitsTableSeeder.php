<?php

namespace Database\Seeders;

use App\Models\UnitSMA;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SMAUnitsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        UnitSMA::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        UnitSMA::insert(['code' => '1L', 'name' => '1 Liter']);
        UnitSMA::insert(['code' => '2L', 'name' => '2 Liters']);
        UnitSMA::insert(['code' => '3L', 'name' => '3 Liters']);
        UnitSMA::insert(['code' => '4L', 'name' => '4 Liters']);
        UnitSMA::insert(['code' => '5L', 'name' => '5 Liters']);
        UnitSMA::insert(['code' => '6L', 'name' => '6 Liters']);
        UnitSMA::insert(['code' => '500ML', 'name' => '500 Milliliter']);
        UnitSMA::insert(['code' => '250ML', 'name' => '250 Milliliter']);
        UnitSMA::insert(['code' => '100ML', 'name' => '100 Milliliter']);
        UnitSMA::insert(['code' => 'E', 'name' => 'EACH']);
    }
}
