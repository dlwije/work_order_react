<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmaCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sma_currencies', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('code', 5);
            $table->string('name', 55);
            $table->decimal('rate', 12, 4);
            $table->tinyInteger('auto_update')->default(0);
            $table->string('symbol', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sma_currencies');
    }
}
