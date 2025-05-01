<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarrantyFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warranty_forms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('warranty_serial')->nullable();
            $table->unsignedInteger('dealer_id')->nullable();
            $table->string('v_make')->nullable();
            $table->string('v_model')->nullable();
            $table->string('v_make_year')->nullable();
            $table->string('engine_no')->nullable();
            $table->string('chassis_no')->nullable();
            $table->string('present_mileage')->nullable();
            $table->date('warranty_date')->nullable();
            $table->string('warranty_bk_no')->nullable();
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
        Schema::dropIfExists('warranty_forms');
    }
}
