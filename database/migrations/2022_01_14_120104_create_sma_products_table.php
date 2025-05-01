<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmaProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sma_products', function (Blueprint $table) {
            $table->integer('id')->index()->autoIncrement();
            $table->string('code', 50)->unique('code');
            $table->string('name');
            $table->integer('unit')->nullable()->index('unit');
            $table->decimal('cost', 25, 4)->nullable();
            $table->decimal('price', 25, 4);
            $table->decimal('alert_quantity', 15, 4)->default(20.0000);
            $table->string('image')->default('no_image.png');
            $table->integer('category_id')->index();
            $table->integer('subcategory_id')->nullable();
            $table->string('cf1')->nullable();
            $table->string('cf2')->nullable();
            $table->string('cf3')->nullable();
            $table->string('cf4')->nullable();
            $table->string('cf5')->nullable();
            $table->string('cf6')->nullable();
            $table->decimal('quantity', 15, 4)->default(0.0000);
            $table->integer('tax_rate')->nullable();
            $table->tinyInteger('track_quantity')->default(1);
            $table->string('details', 1000)->nullable();
            $table->integer('warehouse')->nullable();
            $table->string('barcode_symbology', 55)->default('code128');
            $table->string('file', 100)->nullable();
            $table->text('product_details')->nullable();
            $table->tinyInteger('tax_method')->default(0);
            $table->string('type', 55)->default('standard');
            $table->integer('supplier1')->nullable();
            $table->decimal('supplier1price', 25, 4)->nullable();
            $table->integer('supplier2')->nullable();
            $table->decimal('supplier2price', 25, 4)->nullable();
            $table->integer('supplier3')->nullable();
            $table->decimal('supplier3price', 25, 4)->nullable();
            $table->integer('supplier4')->nullable();
            $table->decimal('supplier4price', 25, 4)->nullable();
            $table->integer('supplier5')->nullable();
            $table->decimal('supplier5price', 25, 4)->nullable();
            $table->tinyInteger('promotion')->default(0);
            $table->decimal('promo_price', 25, 4)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('supplier1_part_no', 50)->nullable();
            $table->string('supplier2_part_no', 50)->nullable();
            $table->string('supplier3_part_no', 50)->nullable();
            $table->string('supplier4_part_no', 50)->nullable();
            $table->string('supplier5_part_no', 50)->nullable();
            $table->integer('sale_unit')->nullable();
            $table->integer('purchase_unit')->nullable();
            $table->integer('brand')->nullable()->index('brand');
            $table->string('slug', 55)->nullable();
            $table->tinyInteger('featured')->nullable();
            $table->decimal('weight', 10, 4)->nullable();
            $table->integer('hsn_code')->nullable();
            $table->integer('views')->default(0);
            $table->tinyInteger('hide')->default(0);
            $table->string('second_name')->nullable();
            $table->boolean('hide_pos')->default(0);
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
        Schema::dropIfExists('sma_products');
    }
}
