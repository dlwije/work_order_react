<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmaBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sma_brands', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('code', 20)->nullable();
            $table->string('name', 50)->index('name');
            $table->string('image', 50)->nullable();
            $table->string('slug', 55)->nullable();
            $table->string('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sma_brands');
    }
}
