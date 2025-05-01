<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateSmaGiftCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sma_gift_cards', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->timestamp('date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('card_no', 20)->unique('card_no');
            $table->decimal('value', 25, 4);
            $table->integer('customer_id')->nullable();
            $table->string('customer')->nullable();
            $table->decimal('balance', 25, 4);
            $table->date('expiry')->nullable();
            $table->string('created_by', 55);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sma_gift_cards');
    }
}
