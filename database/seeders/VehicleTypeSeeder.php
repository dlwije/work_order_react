<?php

namespace Database\Seeders;

use App\Models\VehicleType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehicleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        VehicleType::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        VehicleType::create(['type_name' => 'Car']);
        VehicleType::create(['type_name' => 'SUV']);
        VehicleType::create(['type_name' => 'VAN 1']);
        VehicleType::create(['type_name' => 'JEEP']);
        VehicleType::create(['type_name' => 'VAN 2']);
        VehicleType::create(['type_name' => 'VAN 3']);
        VehicleType::create(['type_name' => 'VAN']);
        VehicleType::create(['type_name' => 'BIKE']);
        VehicleType::create(['type_name' => 'RV']);
    }
}
