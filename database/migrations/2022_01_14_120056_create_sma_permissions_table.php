<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmaPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sma_permissions', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('group_id');
            $table->tinyInteger('products-index')->default(0);
            $table->tinyInteger('products-add')->default(0);
            $table->tinyInteger('products-edit')->default(0);
            $table->tinyInteger('products-delete')->default(0);
            $table->tinyInteger('products-cost')->default(0);
            $table->tinyInteger('products-price')->default(0);
            $table->tinyInteger('quotes-index')->default(0);
            $table->tinyInteger('quotes-add')->default(0);
            $table->tinyInteger('quotes-edit')->default(0);
            $table->tinyInteger('quotes-pdf')->default(0);
            $table->tinyInteger('quotes-email')->default(0);
            $table->tinyInteger('quotes-delete')->default(0);
            $table->tinyInteger('sales-index')->default(0);
            $table->tinyInteger('sales-add')->default(0);
            $table->tinyInteger('sales-edit')->default(0);
            $table->tinyInteger('sales-pdf')->default(0);
            $table->tinyInteger('sales-email')->default(0);
            $table->tinyInteger('sales-delete')->default(0);
            $table->tinyInteger('purchases-index')->default(0);
            $table->tinyInteger('purchases-add')->default(0);
            $table->tinyInteger('purchases-edit')->default(0);
            $table->tinyInteger('purchases-pdf')->default(0);
            $table->boolean('purchases-email')->default(0);
            $table->boolean('purchases-delete')->default(0);
            $table->boolean('transfers-index')->default(0);
            $table->boolean('transfers-add')->default(0);
            $table->boolean('transfers-edit')->default(0);
            $table->boolean('transfers-pdf')->default(0);
            $table->boolean('transfers-email')->default(0);
            $table->boolean('transfers-delete')->default(0);
            $table->boolean('customers-index')->default(0);
            $table->boolean('customers-add')->default(0);
            $table->boolean('customers-edit')->default(0);
            $table->boolean('customers-delete')->default(0);
            $table->boolean('suppliers-index')->default(0);
            $table->boolean('suppliers-add')->default(0);
            $table->boolean('suppliers-edit')->default(0);
            $table->boolean('suppliers-delete')->default(0);
            $table->boolean('sales-deliveries')->default(0);
            $table->boolean('sales-add_delivery')->default(0);
            $table->boolean('sales-edit_delivery')->default(0);
            $table->boolean('sales-delete_delivery')->default(0);
            $table->boolean('sales-email_delivery')->default(0);
            $table->boolean('sales-pdf_delivery')->default(0);
            $table->boolean('sales-gift_cards')->default(0);
            $table->boolean('sales-add_gift_card')->default(0);
            $table->boolean('sales-edit_gift_card')->default(0);
            $table->boolean('sales-delete_gift_card')->default(0);
            $table->boolean('pos-index')->default(0);
            $table->boolean('sales-return_sales')->default(0);
            $table->boolean('reports-index')->default(0);
            $table->boolean('reports-warehouse_stock')->default(0);
            $table->boolean('reports-quantity_alerts')->default(0);
            $table->boolean('reports-expiry_alerts')->default(0);
            $table->boolean('reports-products')->default(0);
            $table->boolean('reports-daily_sales')->default(0);
            $table->boolean('reports-monthly_sales')->default(0);
            $table->boolean('reports-sales')->default(0);
            $table->boolean('reports-payments')->default(0);
            $table->boolean('reports-purchases')->default(0);
            $table->boolean('reports-profit_loss')->default(0);
            $table->boolean('reports-customers')->default(0);
            $table->boolean('reports-suppliers')->default(0);
            $table->boolean('reports-staff')->default(0);
            $table->boolean('reports-register')->default(0);
            $table->boolean('sales-payments')->default(0);
            $table->boolean('purchases-payments')->default(0);
            $table->boolean('purchases-expenses')->default(0);
            $table->boolean('products-adjustments')->default(0);
            $table->boolean('bulk_actions')->default(0);
            $table->boolean('customers-deposits')->default(0);
            $table->boolean('customers-delete_deposit')->default(0);
            $table->boolean('products-barcode')->default(0);
            $table->boolean('purchases-return_purchases')->default(0);
            $table->boolean('reports-expenses')->default(0);
            $table->boolean('reports-daily_purchases')->default(0);
            $table->boolean('reports-monthly_purchases')->default(0);
            $table->boolean('products-stock_count')->default(0);
            $table->boolean('edit_price')->default(0);
            $table->boolean('returns-index')->default(0);
            $table->boolean('returns-add')->default(0);
            $table->boolean('returns-edit')->default(0);
            $table->boolean('returns-delete')->default(0);
            $table->boolean('returns-email')->default(0);
            $table->boolean('returns-pdf')->default(0);
            $table->boolean('reports-tax')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sma_permissions');
    }
}
