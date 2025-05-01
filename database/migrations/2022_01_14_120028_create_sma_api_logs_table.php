<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmaApiLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sma_api_logs', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('uri');
            $table->string('method', 6);
            $table->text('params')->nullable();
            $table->string('api_key', 40);
            $table->string('ip_address', 45);
            $table->integer('time');
            $table->float('rtime')->nullable();
            $table->string('authorized', 1);
            $table->smallInteger('response_code')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sma_api_logs');
    }
}
