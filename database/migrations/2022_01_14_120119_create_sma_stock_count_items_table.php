<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmaStockCountItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sma_stock_count_items', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('stock_count_id')->index('stock_count_id');
            $table->integer('product_id')->index('product_id');
            $table->string('product_code', 50)->nullable();
            $table->string('product_name')->nullable();
            $table->string('product_variant', 55)->nullable();
            $table->integer('product_variant_id')->nullable();
            $table->decimal('expected', 15, 4);
            $table->decimal('counted', 15, 4);
            $table->decimal('cost', 25, 4);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sma_stock_count_items');
    }
}
