<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Employee::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Employee::create(['emp_no' => 'EMP0001', 'first_name' => 'Al', 'last_name' => 'mansouri', 'full_name' => 'Al mansouri']);
        Employee::create(['emp_no' => 'EMP0002', 'first_name' => 'Al', 'last_name' => 'mazrouei', 'full_name' => 'Al mazrouei']);
        Employee::create(['emp_no' => 'EMP0003', 'first_name' => 'El', 'last_name' => 'sewedy', 'full_name' => 'El sewedy']);
        Employee::create(['emp_no' => 'EMP0004', 'first_name' => 'Al', 'last_name' => 'qubaisi', 'full_name' => 'Al qubaisi']);
        Employee::create(['emp_no' => 'EMP0005', 'first_name' => 'Agnus', 'last_name' => 'Maria H', 'full_name' => 'Agnus Maria H']);
        Employee::create(['emp_no' => 'EMP0006', 'first_name' => 'Reza ul', 'last_name' => 'karim rana', 'full_name' => 'Reza ul karim rana']);
    }
}
