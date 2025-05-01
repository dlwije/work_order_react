<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmaProductPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sma_product_prices', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('product_id')->index('product_id');
            $table->integer('price_group_id')->index('price_group_id');
            $table->decimal('price', 25, 4);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sma_product_prices');
    }
}
