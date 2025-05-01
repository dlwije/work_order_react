<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateSmaStockCountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sma_stock_counts', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->timestamp('date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('reference_no', 55);
            $table->integer('warehouse_id')->index('warehouse_id');
            $table->string('type', 10);
            $table->string('initial_file', 50);
            $table->string('final_file', 50)->nullable();
            $table->string('brands', 50)->nullable();
            $table->string('brand_names', 100)->nullable();
            $table->string('categories', 50)->nullable();
            $table->string('category_names', 100)->nullable();
            $table->text('note')->nullable();
            $table->integer('products')->nullable();
            $table->integer('rows')->nullable();
            $table->integer('differences')->nullable();
            $table->integer('matches')->nullable();
            $table->integer('missing')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->tinyInteger('finalized')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sma_stock_counts');
    }
}
