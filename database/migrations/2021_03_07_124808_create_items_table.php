<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('item_name');
            $table->string('item_full_name')->nullable();
            $table->text('item_description')->nullable();
            $table->text('sales_description')->nullable();
            $table->unsignedInteger('item_type')->nullable();
            $table->string('manufac_no')->nullable();
            $table->unsignedInteger('uom_id')->nullable();
            $table->double('discount')->default(0.00);
            $table->double('item_cost')->default(0.00);
            $table->double('sale_price')->default(0.00);
            $table->double('on_hand')->default(0.00);
            $table->double('reorder_min')->default(1.00);
            $table->double('reorder_max')->nullable();
            $table->integer('service_mileage')->nullable()->default(0);
            $table->unsignedInteger('pref_vendor_id')->nullable();
            $table->unsignedInteger('item_account_id')->nullable();
            $table->unsignedInteger('asset_account_id')->nullable();
            $table->unsignedInteger('income_account_id')->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();

            $table->foreign('item_type')->references('id')->on('item_types')
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
