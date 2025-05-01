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
        Schema::table('invoice_serv_packages', function (Blueprint $table) {
            $table->decimal('discount', 15, 2)->nullable()->after('price')->default(0);
            $table->decimal('tot_discount', 15, 2)->nullable()->after('discount')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice_serv_packages', function (Blueprint $table) {
            $table->dropColumn('discount');
            $table->dropColumn('tot_discount');
        });
    }
};
