<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateSmaDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sma_deliveries', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->timestamp('date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('sale_id');
            $table->string('do_reference_no', 50);
            $table->string('sale_reference_no', 50);
            $table->string('customer', 55);
            $table->string('address', 1000);
            $table->string('note', 1000)->nullable();
            $table->string('status', 15)->nullable();
            $table->string('attachment', 50)->nullable();
            $table->string('delivered_by', 50)->nullable();
            $table->string('received_by', 50)->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sma_deliveries');
    }
}
