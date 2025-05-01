<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehicleContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_contracts', function (Blueprint $table) {
            $table->id();
            $table->string('serial_no',190)->nullable();
            $table->unsignedBigInteger('wo_id')->nullable();
            $table->integer('customer_id')->nullable();
            $table->unsignedInteger('vehicle_id')->nullable();
            $table->string('con_company_name',190)->nullable();
            $table->string('contract_no',190)->nullable();
            $table->date('expire_date')->default(date('Y-m-d'))->nullable();
            $table->integer('mileage')->nullable();
            $table->string('phone_number',15)->nullable();
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
        Schema::dropIfExists('vehicle_contracts');
    }
}
