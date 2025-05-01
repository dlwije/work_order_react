<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmaSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sma_sessions', function (Blueprint $table) {
            $table->string('id', 40)->primary();
            $table->string('ip_address', 45);
            $table->unsignedInteger('timestamp')->default(0)->index('ci_sessions_timestamp');
            $table->binary('data');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sma_sessions');
    }
}
