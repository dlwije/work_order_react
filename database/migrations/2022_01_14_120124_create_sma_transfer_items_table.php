<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmaTransferItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sma_transfer_items', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('transfer_id')->index('transfer_id');
            $table->integer('product_id')->index('product_id');
            $table->string('product_code', 55);
            $table->string('product_name');
            $table->integer('option_id')->nullable();
            $table->date('expiry')->nullable();
            $table->decimal('quantity', 15, 4);
            $table->integer('tax_rate_id')->nullable();
            $table->string('tax', 55)->nullable();
            $table->decimal('item_tax', 25, 4)->nullable();
            $table->decimal('net_unit_cost', 25, 4)->nullable();
            $table->decimal('subtotal', 25, 4)->nullable();
            $table->decimal('quantity_balance', 15, 4);
            $table->decimal('unit_cost', 25, 4)->nullable();
            $table->decimal('real_unit_cost', 25, 4)->nullable();
            $table->date('date')->nullable();
            $table->integer('warehouse_id')->nullable();
            $table->integer('product_unit_id')->nullable();
            $table->string('product_unit_code', 10)->nullable();
            $table->decimal('unit_quantity', 15, 4);
            $table->string('gst', 20)->nullable();
            $table->decimal('cgst', 25, 4)->nullable();
            $table->decimal('sgst', 25, 4)->nullable();
            $table->decimal('igst', 25, 4)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sma_transfer_items');
    }
}
