<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmaPurchaseItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sma_purchase_items', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('purchase_id')->nullable()->index('purchase_id');
            $table->integer('transfer_id')->nullable();
            $table->integer('product_id')->index('product_id');
            $table->string('product_code', 50);
            $table->string('product_name');
            $table->integer('option_id')->nullable();
            $table->decimal('net_unit_cost', 25, 4);
            $table->decimal('quantity', 15, 4);
            $table->integer('warehouse_id');
            $table->decimal('item_tax', 25, 4)->nullable();
            $table->integer('tax_rate_id')->nullable();
            $table->string('tax', 20)->nullable();
            $table->string('discount', 20)->nullable();
            $table->decimal('item_discount', 25, 4)->nullable();
            $table->date('expiry')->nullable();
            $table->decimal('subtotal', 25, 4);
            $table->decimal('quantity_balance', 15, 4)->default(0.0000);
            $table->date('date');
            $table->string('status', 50);
            $table->decimal('unit_cost', 25, 4)->nullable();
            $table->decimal('real_unit_cost', 25, 4)->nullable();
            $table->decimal('quantity_received', 15, 4)->nullable();
            $table->string('supplier_part_no', 50)->nullable();
            $table->integer('purchase_item_id')->nullable();
            $table->integer('product_unit_id')->nullable();
            $table->string('product_unit_code', 10)->nullable();
            $table->decimal('unit_quantity', 15, 4);
            $table->string('gst', 20)->nullable();
            $table->decimal('cgst', 25, 4)->nullable();
            $table->decimal('sgst', 25, 4)->nullable();
            $table->decimal('igst', 25, 4)->nullable();
            $table->decimal('base_unit_cost', 25, 4)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sma_purchase_items');
    }
}
