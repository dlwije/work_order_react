<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sma_sales', function (Blueprint $table) {
            $table->decimal('total_serv', 15, 2)->nullable()->after('staff_note')->default(0);
            $table->decimal('total_serv_discount', 15, 2)->nullable()->after('total')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sma_sales', function (Blueprint $table) {
            $table->dropColumn('total_serv');
            $table->dropColumn('total_serv_discount');
        });
    }
};
