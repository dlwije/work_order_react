<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmaPaypalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sma_paypal', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->tinyInteger('active');
            $table->string('account_email');
            $table->string('paypal_currency', 3)->default('USD');
            $table->decimal('fixed_charges', 25, 4)->default(2.0000);
            $table->decimal('extra_charges_my', 25, 4)->default(3.9000);
            $table->decimal('extra_charges_other', 25, 4)->default(4.4000);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sma_paypal');
    }
}
