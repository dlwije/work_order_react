<?php

namespace Database\Seeders;

use App\Models\WarehouseSMA;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SMAWarehouseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        WarehouseSMA::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        WarehouseSMA::insert(['code' => 'WH01', 'name' => 'Warehouse 01','address' => 'Warehouse 01, Dubai, UAE']);
        WarehouseSMA::insert(['code' => 'WH02', 'name' => 'Warehouse 02','address' => 'Warehouse 02, Dubai, UAE']);
    }
}
