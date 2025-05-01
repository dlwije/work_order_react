<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateSmaAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sma_addresses', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('company_id')->index('company_id');
            $table->string('line1', 50);
            $table->string('line2', 50)->nullable();
            $table->string('city', 25);
            $table->string('postal_code', 20)->nullable();
            $table->string('state', 25);
            $table->string('country', 50);
            $table->string('phone', 50)->nullable();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sma_addresses');
    }
}
