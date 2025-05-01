<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmaCaptchaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sma_captcha', function (Blueprint $table) {
            $table->bigIncrements('captcha_id');
            $table->unsignedInteger('captcha_time');
            $table->string('ip_address', 16)->default('0');
            $table->string('word', 20)->index('word');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sma_captcha');
    }
}
