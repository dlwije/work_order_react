<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmaApiKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sma_api_keys', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('user_id');
            $table->string('reference', 40);
            $table->string('key', 40);
            $table->integer('level');
            $table->boolean('ignore_limits')->default(0);
            $table->boolean('is_private_key')->default(0);
            $table->text('ip_addresses')->nullable();
            $table->integer('date_created');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sma_api_keys');
    }
}
