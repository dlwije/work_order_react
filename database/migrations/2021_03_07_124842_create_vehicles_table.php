<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('customer_id');
            $table->year('make_year')->default(date('Y'))->nullable();
            $table->string('vehicle_make',50)->nullable();
            $table->string('vehicle_model',50)->nullable();
            $table->string('vehicle_no',50)->nullable();
            $table->string('chassis_no')->nullable();
            $table->string('engine_no')->nullable();// VIN
            $table->unsignedInteger('vehicle_type_id')->nullable();
            $table->tinyInteger('vehicle_type_state')->default(1)->nullable()->comment('1=motor_home, 0=trailer');//if 0 then trailer elseif 1 motor-home
            $table->string('sub_model',190)->nullable();
            $table->string('body',190)->nullable();
            $table->integer('odometer')->nullable();
            $table->string('unit_number',190)->nullable();
            $table->string('color',190)->nullable();
            $table->string('transmission',50)->nullable();
            $table->tinyInteger('is_active')->default(1)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
}
