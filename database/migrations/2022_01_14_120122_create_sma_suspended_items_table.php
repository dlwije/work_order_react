<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmaSuspendedItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sma_suspended_items', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->unsignedInteger('suspend_id');
            $table->morphs('product');
            $table->string('product_code', 55);
            $table->string('product_name');
            $table->decimal('net_unit_price', 25, 4);
            $table->decimal('unit_price', 25, 4);
            $table->decimal('quantity', 15, 4)->default(0.0000);
            $table->integer('warehouse_id')->nullable();
            $table->decimal('item_tax', 25, 4)->nullable();
            $table->integer('tax_rate_id')->nullable();
            $table->string('tax', 55)->nullable();
            $table->string('discount', 55)->nullable();
            $table->decimal('item_discount', 25, 4)->nullable();
            $table->decimal('subtotal', 25, 4);
            $table->string('serial_no')->nullable();
            $table->integer('option_id')->nullable();
            $table->decimal('real_unit_price', 25, 4)->nullable();
            $table->integer('product_unit_id')->nullable();
            $table->string('product_unit_code', 10)->nullable();
            $table->decimal('unit_quantity', 15, 4);
            $table->string('comment')->nullable();
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
        Schema::dropIfExists('sma_suspended_items');
    }
}
