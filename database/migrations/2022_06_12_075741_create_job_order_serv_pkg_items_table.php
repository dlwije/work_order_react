<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobOrderServPkgItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_order_serv_pkg_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jo_serv_pkg_id')->nullable();
            $table->unsignedBigInteger('service_pkg_id')->nullable();
            $table->unsignedInteger('product_id')->nullable();
            $table->string('product_code', 199)->nullable();
            $table->string('product_name', 199)->nullable();
            $table->string('product_type', 199)->nullable();
            $table->unsignedInteger('item_option_id')->nullable();
            $table->double('net_unit_price')->nullable()->default(0.00);
            $table->double('net_unit_price_old')->nullable()->default(0.00);
            $table->double('unit_price')->nullable()->default(0.00);
            $table->double('unit_price_old')->nullable()->default(0.00);
            $table->double('quantity')->nullable()->default(0.00);
            $table->unsignedInteger('warehouse_id')->nullable();
            $table->decimal('item_tax',25,4)->nullable();
            $table->unsignedInteger('tax_rate_id')->nullable();
            $table->string('tax',20)->nullable();
            $table->string('discount',20)->nullable();
            $table->decimal('item_discount',25,4)->nullable();
            $table->decimal('subtotal',25,4)->nullable();
            $table->decimal('real_unit_price',25,4)->nullable();
            $table->decimal('real_unit_price_old',25,4)->nullable();
            $table->unsignedInteger('sale_item_id')->nullable();
            $table->unsignedInteger('product_unit_id')->nullable();
            $table->string('product_unit_code',10)->nullable();
            $table->decimal('unit_quantity',15,4)->nullable();
            $table->text('comment')->nullable();

            $table->timestamps();

            $table->foreign('jo_serv_pkg_id')->references('id')->on('job_order_serv_packages')
                ->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_order_serv_pkg_items');
    }
}
