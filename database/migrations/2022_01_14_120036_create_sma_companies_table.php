<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmaCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sma_companies', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('old_db_id')->nullable();
            $table->unsignedInteger('group_id')->nullable()->index();
            $table->string('group_name', 20);
            $table->integer('customer_group_id')->nullable();
            $table->string('customer_group_name', 100)->nullable();
            $table->string('name', 55);
            $table->string('first_name', 55)->nullable();
            $table->string('last_name', 55)->nullable();
            $table->string('spouse_first_name', 55)->nullable();
            $table->string('spouse_last_name', 55)->nullable();
            $table->string('company');
            $table->string('vat_no', 100)->nullable();
            $table->string('address')->nullable();
            $table->string('city', 55)->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->string('state', 55)->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->string('postal_code', 8)->nullable();
            $table->string('country', 100)->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email', 100);
            $table->string('cf1', 100)->nullable();
            $table->string('cf2', 100)->nullable();
            $table->string('cf3', 100)->nullable();
            $table->string('cf4', 100)->nullable();
            $table->string('cf5', 100)->nullable();
            $table->string('cf6', 100)->nullable();
            $table->text('invoice_footer')->nullable();
            $table->integer('payment_term')->default(0);
            $table->string('logo')->default('logo.png');
            $table->integer('award_points')->default(0);
            $table->decimal('deposit_amount', 25, 4)->nullable();
            $table->integer('price_group_id')->nullable();
            $table->string('price_group_name', 50)->nullable();
            $table->string('gst_no', 100)->nullable();
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
        Schema::dropIfExists('sma_companies');
    }
}
