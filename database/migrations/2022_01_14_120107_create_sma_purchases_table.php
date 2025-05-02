<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateSmaPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sma_purchases', function (Blueprint $table) {
            $table->integer('id')->index()->autoIncrement();
            $table->string('reference_no', 55);
            $table->timestamp('date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('supplier_id');
            $table->string('supplier', 55);
            $table->integer('warehouse_id');
            $table->string('note', 1000);
            $table->decimal('total', 25, 4)->nullable();
            $table->decimal('product_discount', 25, 4)->nullable();
            $table->string('order_discount_id', 20)->nullable();
            $table->decimal('order_discount', 25, 4)->nullable();
            $table->decimal('total_discount', 25, 4)->nullable();
            $table->decimal('product_tax', 25, 4)->nullable();
            $table->integer('order_tax_id')->nullable();
            $table->decimal('order_tax', 25, 4)->nullable();
            $table->decimal('total_tax', 25, 4)->default(0.0000);
            $table->decimal('shipping', 25, 4)->default(0.0000);
            $table->decimal('grand_total', 25, 4);
            $table->decimal('paid', 25, 4)->default(0.0000);
            $table->string('status', 55)->default('');
            $table->string('payment_status', 20)->default('pending');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
//            $table->timestamp('updated_at')->nullable();
            $table->string('attachment', 55)->nullable();
            $table->tinyInteger('payment_term')->nullable();
            $table->date('due_date')->nullable();
            $table->integer('return_id')->nullable();
            $table->decimal('surcharge', 25, 4)->default(0.0000);
            $table->string('return_purchase_ref', 55)->nullable();
            $table->integer('purchase_id')->nullable();
            $table->decimal('return_purchase_total', 25, 4)->default(0.0000);
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
        Schema::dropIfExists('sma_purchases');
    }
}
