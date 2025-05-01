<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMJobCardDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_job_card_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('header_id')->nullable();
            $table->unsignedInteger('item_id')->nullable();
            $table->unsignedInteger('item_type_id')->nullable();
//            $table->unsignedInteger('warranty_id')->nullable();
            $table->double('qty')->default(0);
            $table->double('rate')->default(0);
            $table->double('amount')->default(0);
            $table->double('job_hours')->default(0);
            $table->text('remark')->nullable();
            $table->timestamps();

            $table->foreign('header_id')->references('id')->on('m_job_card_masters')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('item_id')->references('id')->on('items')
                ->cascadeOnUpdate();
            $table->foreign('item_type_id')->references('id')->on('item_types')
                ->cascadeOnUpdate();
            /*$table->foreign('warranty_id')->references('id')->on('warranty_periods')
                ->cascadeOnUpdate();*/
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_job_card_details');
    }
}
