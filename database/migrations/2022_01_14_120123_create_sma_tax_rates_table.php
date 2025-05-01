<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmaTaxRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sma_tax_rates', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('name', 55);
            $table->string('code', 10)->nullable();
            $table->decimal('rate', 12, 4);
            $table->string('type', 50);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sma_tax_rates');
    }
}
