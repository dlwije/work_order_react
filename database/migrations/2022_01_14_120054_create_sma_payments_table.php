<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateSmaPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sma_payments', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->timestamp('date')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('sale_id')->nullable();
            $table->integer('return_id')->nullable();
            $table->integer('purchase_id')->nullable();
            $table->string('reference_no', 50);
            $table->string('transaction_id', 50)->nullable();
            $table->string('paid_by', 20);
            $table->string('cheque_no', 20)->nullable();
            $table->string('cc_no', 20)->nullable();
            $table->string('cc_holder', 25)->nullable();
            $table->string('cc_month', 2)->nullable();
            $table->string('cc_year', 4)->nullable();
            $table->string('cc_type', 20)->nullable();
            $table->decimal('amount', 25, 4);
            $table->string('currency', 3)->nullable();
            $table->integer('created_by');
            $table->string('attachment', 55)->nullable();
            $table->string('type', 20);
            $table->string('note', 1000)->nullable();
            $table->decimal('pos_paid', 25, 4)->default(0.0000);
            $table->decimal('pos_balance', 25, 4)->default(0.0000);
            $table->string('approval_code', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sma_payments');
    }
}
