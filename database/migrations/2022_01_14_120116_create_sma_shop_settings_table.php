<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmaShopSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sma_shop_settings', function (Blueprint $table) {
            $table->integer('shop_id')->autoIncrement();
            $table->string('shop_name', 55);
            $table->string('description', 160);
            $table->integer('warehouse');
            $table->integer('biller');
            $table->string('about_link', 55);
            $table->string('terms_link', 55);
            $table->string('privacy_link', 55);
            $table->string('contact_link', 55);
            $table->string('payment_text', 100);
            $table->string('follow_text', 100);
            $table->string('facebook', 55);
            $table->string('twitter', 55)->nullable();
            $table->string('google_plus', 55)->nullable();
            $table->string('instagram', 55)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('email', 55)->nullable();
            $table->string('cookie_message', 180)->nullable();
            $table->string('cookie_link', 55)->nullable();
            $table->text('slider')->nullable();
            $table->integer('shipping')->nullable();
            $table->string('purchase_code', 100)->default('purchase_code');
            $table->string('envato_username', 50)->default('envato_username');
            $table->string('version', 10)->default('3.4.47');
            $table->string('logo', 55)->nullable();
            $table->string('bank_details')->nullable();
            $table->tinyInteger('products_page')->nullable();
            $table->tinyInteger('hide0')->default(0);
            $table->string('products_description')->nullable();
            $table->tinyInteger('private')->default(0);
            $table->tinyInteger('hide_price')->default(0);
            $table->tinyInteger('stripe')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sma_shop_settings');
    }
}
