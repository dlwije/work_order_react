<?php

namespace Database\Seeders;

use App\Models\FormSerial;
use Illuminate\Database\Seeder;

class FormSerialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        FormSerial::truncate();

        FormSerial::create(['form' => 'work_order', 'prefix' => 'WO', 'num' => 00001]);
        FormSerial::create(['form' => 'job_order', 'prefix' => 'JO', 'num' => 00001]);
    }
}
