<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmaCalendarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sma_calendar', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('title', 55);
            $table->string('description')->nullable();
            $table->dateTime('start');
            $table->dateTime('end')->nullable();
            $table->string('color', 7);
            $table->integer('user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sma_calendar');
    }
}
