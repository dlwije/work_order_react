<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->string('serial_no',190)->nullable();
            $table->integer('customer_id')->nullable(); //this connects with the 'sma_companies' table id column
            $table->unsignedInteger('vehicle_id')->nullable(); //this connects with the 'vehicles' table id column
            $table->date('wo_date')->nullable();
            $table->string('appointment_id',190)->nullable();
            $table->string('mileage',190)->nullable();
            $table->text('reported_defect')->nullable();
            /*$table->unsignedInteger('warranty_id')->nullable();//this connects with the 'warranty_forms' table id column
            $table->unsignedInteger('contract_id')->nullable();//this connects with the 'vehicle_contracts' table id column
            $table->unsignedInteger('insurance_id')->nullable();//this connects with the 'vehicle_insurances' table id column*/
            $table->tinyInteger('is_warranty')->default(0);//have Warranty this will be 1
            $table->tinyInteger('is_contract')->default(0);//have Contract this will be 1
            $table->tinyInteger('is_insurance')->default(0);//have Insurance this will be 1
            $table->tinyInteger('wo_status')->default(0);
            $table->tinyInteger('wo_type')->default(0)->comment('0=WO, 1=warranty/contract/insurance WO'); //0=Non-warranty WO, 1=warranty WO
            $table->tinyInteger('is_loaded_jo')->default(0);
            $table->tinyInteger('is_active')->default(1);
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
        Schema::dropIfExists('work_orders');
    }
}
