<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNonInvenGrnDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('non_inven_grn_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('header_id')->nullable();
            $table->unsignedInteger('item_id')->nullable();
            $table->unsignedInteger('item_type_id')->nullable();
            $table->double('qty')->default(0);
            $table->timestamps();

            $table->foreign('header_id')->references('id')->on('non_inven_grn_masters')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('item_id')->references('id')->on('items')
                ->cascadeOnUpdate();
            $table->foreign('item_type_id')->references('id')->on('item_types')
                ->cascadeOnUpdate();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('non_inven_grn_details');
    }
}
