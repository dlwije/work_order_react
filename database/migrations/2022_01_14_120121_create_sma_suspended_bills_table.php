<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateSmaSuspendedBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sma_suspended_bills', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->timestamp('date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('customer_id');
            $table->string('customer', 55)->nullable();
            $table->integer('count');
            $table->string('order_discount_id', 20)->nullable();
            $table->integer('order_tax_id')->nullable();
            $table->decimal('total', 25, 4);
            $table->integer('biller_id')->nullable();
            $table->integer('warehouse_id')->nullable();
            $table->integer('created_by');
            $table->string('suspend_note')->nullable();
            $table->decimal('shipping', 15, 4)->default(0.0000);
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
        Schema::dropIfExists('sma_suspended_bills');
    }
}
