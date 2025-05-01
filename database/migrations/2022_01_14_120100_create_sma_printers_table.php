<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmaPrintersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sma_printers', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('title', 55);
            $table->string('type', 25);
            $table->string('profile', 25);
            $table->unsignedTinyInteger('char_per_line')->nullable();
            $table->string('path')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('port', 10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sma_printers');
    }
}
