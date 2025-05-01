<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmaPosSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sma_pos_settings', function (Blueprint $table) {
            $table->integer('pos_id')->autoIncrement();
            $table->integer('cat_limit');
            $table->integer('pro_limit');
            $table->integer('default_category');
            $table->integer('default_customer');
            $table->integer('default_biller');
            $table->string('display_time', 3)->default('yes');
            $table->string('cf_title1')->nullable();
            $table->string('cf_title2')->nullable();
            $table->string('cf_value1')->nullable();
            $table->string('cf_value2')->nullable();
            $table->string('receipt_printer', 55)->nullable();
            $table->string('cash_drawer_codes', 55)->nullable();
            $table->string('focus_add_item', 55)->nullable();
            $table->string('add_manual_product', 55)->nullable();
            $table->string('customer_selection', 55)->nullable();
            $table->string('add_customer', 55)->nullable();
            $table->string('toggle_category_slider', 55)->nullable();
            $table->string('toggle_subcategory_slider', 55)->nullable();
            $table->string('cancel_sale', 55)->nullable();
            $table->string('suspend_sale', 55)->nullable();
            $table->string('print_items_list', 55)->nullable();
            $table->string('finalize_sale', 55)->nullable();
            $table->string('today_sale', 55)->nullable();
            $table->string('open_hold_bills', 55)->nullable();
            $table->string('close_register', 55)->nullable();
            $table->tinyInteger('keyboard');
            $table->string('pos_printers')->nullable();
            $table->tinyInteger('java_applet');
            $table->string('product_button_color', 20)->default('default');
            $table->boolean('tooltips')->default(1);
            $table->boolean('paypal_pro')->default(0);
            $table->boolean('stripe')->default(0);
            $table->boolean('rounding')->default(0);
            $table->tinyInteger('char_per_line')->default(42);
            $table->string('pin_code', 20)->nullable();
            $table->string('purchase_code', 100)->default('purchase_code');
            $table->string('envato_username', 50)->default('envato_username');
            $table->string('version', 10)->default('3.4.47');
            $table->boolean('after_sale_page')->default(0);
            $table->boolean('item_order')->default(0);
            $table->boolean('authorize')->default(0);
            $table->string('toggle_brands_slider', 55)->nullable();
            $table->boolean('remote_printing')->default(1);
            $table->integer('printer')->nullable();
            $table->string('order_printers', 55)->nullable();
            $table->boolean('auto_print')->default(0);
            $table->tinyInteger('customer_details')->nullable();
            $table->tinyInteger('local_printers')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sma_pos_settings');
    }
}
