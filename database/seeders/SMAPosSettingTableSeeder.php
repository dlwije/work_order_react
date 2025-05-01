<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SMAPosSettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('sma_pos_settings')->truncate();

        DB::table('sma_pos_settings')->insert([
            'pos_id' => 1,
            'cat_limit' => 22,
            'pro_limit' => 20,
            'default_category' => 1,
            'default_customer' => 1,
            'default_biller' => 3,
            'display_time' => '1',
            'cf_title1' => 'GST Reg',
            'cf_title2' => 'VAT Reg',
            'cf_value1' => '123456789',
            'cf_value2' => '987654321',
            'receipt_printer' => 'BIXOLON SRP-350II',
            'cash_drawer_codes' => 'x1C',
            'focus_add_item' => 'Ctrl+F3',
            'add_manual_product' => 'Ctrl+Shift+M',
            'customer_selection' => 'Ctrl+Shift+C',
            'add_customer' => 'Ctrl+Shift+A',
            'toggle_category_slider' => 'Ctrl+F11',
            'toggle_subcategory_slider' => 'Ctrl+F12',
            'cancel_sale' => 'F4',
            'suspend_sale' => 'F7',
            'print_items_list' => 'F9',
            'finalize_sale' => 'F8',
            'today_sale' => 'Ctrl+F1',
            'open_hold_bills' => 'Ctrl+F2',
            'close_register' => 'Ctrl+F10',
            'keyboard' => 1,
            'pos_printers' => 'BIXOLON SRP-350II, BIXOLON SRP-350II',
            'java_applet' => 0,
            'product_button_color' => 'default',
            'tooltips' => 1,
            'paypal_pro' => 0,
            'stripe' => 0,
            'rounding' => 0,
            'char_per_line' => 42,
            'pin_code' => NULL,
            'purchase_code' => 'purchase_code',
            'envato_username' => 'envato_username',
            'version' => '3.4.47',
            'after_sale_page' => 0,
            'item_order' => 0,
            'authorize' => 0,
            'toggle_brands_slider' => NULL,
            'remote_printing' => 1,
            'printer' => NULL,
            'order_printers' => NULL,
            'auto_print' => 0,
            'customer_details' => NULL,
            'local_printers' => NULL,
        ]);
    }
}
