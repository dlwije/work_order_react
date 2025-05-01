<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmaWarehousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sma_warehouses', function (Blueprint $table) {
            $table->integer('id')->index()->autoIncrement();
            $table->string('code', 50);
            $table->string('name');
            $table->string('address');
            $table->string('map')->nullable();
            $table->string('phone', 55)->nullable();
            $table->string('email', 55)->nullable();
            $table->integer('price_group_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sma_warehouses');
    }
}
