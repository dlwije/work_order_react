<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SMAPermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('sma_permissions')->truncate();

        DB::table('sma_permissions')->insert([
            'id' => 1,
            'group_id' => 5,
            'products-index' => 1,
            'products-add' => 0,
            'products-edit' => 0,
            'products-delete' => 0,
            'products-cost' => 0,
            'products-price' => 0,
            'quotes-index' => 1,
            'quotes-add' => 1,
            'quotes-edit' => 1,
            'quotes-pdf' => 1,
            'quotes-email' => 1,
            'quotes-delete' => 0,
            'sales-index' => 1,
            'sales-add' => 1,
            'sales-edit' => 0,
            'sales-pdf' => 1,
            'sales-email' => 1,
            'sales-delete' => 0,
            'purchases-index' => 0,
            'purchases-add' => 0,
            'purchases-edit' => 0,
            'purchases-pdf' => 0,
            'purchases-email' => 0,
            'purchases-delete' => 0,
            'transfers-index' => 0,
            'transfers-add' => 0,
            'transfers-edit' => 0,
            'transfers-pdf' => 0,
            'transfers-email' => 0,
            'transfers-delete' => 0,
            'customers-index' => 1,
            'customers-add' => 1,
            'customers-edit' => 1,
            'customers-delete' => 0,
            'suppliers-index' => 0,
            'suppliers-add' => 0,
            'suppliers-edit' => 0,
            'suppliers-delete' => 0,
            'sales-deliveries' => 1,
            'sales-add_delivery' => 1,
            'sales-edit_delivery' => 1,
            'sales-delete_delivery' => 0,
            'sales-email_delivery' => 0,
            'sales-pdf_delivery' => 1,
            'sales-gift_cards' => 1,
            'sales-add_gift_card' => 1,
            'sales-edit_gift_card' => 0,
            'sales-delete_gift_card' => 0,
            'pos-index' => 1,
            'sales-return_sales' => 0,
            'reports-index' => 0,
            'reports-warehouse_stock' => 0,
            'reports-quantity_alerts' => 0,
            'reports-expiry_alerts' => 0,
            'reports-products' => 0,
            'reports-daily_sales' => 0,
            'reports-monthly_sales' => 0,
            'reports-sales' => 0,
            'reports-payments' => 0,
            'reports-purchases' => 0,
            'reports-profit_loss' => 0,
            'reports-customers' => 0,
            'reports-suppliers' => 0,
            'reports-staff' => 0,
            'reports-register' => 0,
            'sales-payments' => 0,
            'purchases-payments' => 0,
            'purchases-expenses' => 0,
            'products-adjustments' => 0,
            'bulk_actions' => 0,
            'customers-deposits' => 0,
            'customers-delete_deposit' => 0,
            'products-barcode' => 0,
            'purchases-return_purchases' => 0,
            'reports-expenses' => 0,
            'reports-daily_purchases' => 0,
            'reports-monthly_purchases' => 0,
            'products-stock_count' => 0,
            'edit_price' => 0,
            'returns-index' => 0,
            'returns-add' => 0,
            'returns-edit' => 0,
            'returns-delete' => 0,
            'returns-email' => 0,
            'returns-pdf' => 0,
            'reports-tax' => 0,
        ]);
    }
}
