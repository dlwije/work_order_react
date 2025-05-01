<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceServPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_serv_packages', function (Blueprint $table) {
            $table->id();
            $table->integer('sale_id')->nullable();
            $table->unsignedInteger('service_pkg_id')->nullable();
            $table->unsignedInteger('vehi_type_id')->nullable();
            $table->double('cost')->default(0.00);
            $table->double('price')->default(0.00);
            $table->double('sub_total')->default(0.00);
            $table->timestamps();

            /*$table->foreign('sale_id')->references('id')->on('sma_sales')
                ->cascadeOnDelete()->cascadeOnUpdate();*/
            /*$table->foreign('service_pkg_id')->references('id')->on('service_packages')
                ->cascadeOnDelete()->cascadeOnUpdate();*/
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_serv_packages');
    }
}
