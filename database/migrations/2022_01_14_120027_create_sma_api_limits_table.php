<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmaApiLimitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sma_api_limits', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('uri');
            $table->integer('count');
            $table->integer('hour_started');
            $table->string('api_key', 40);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sma_api_limits');
    }
}
