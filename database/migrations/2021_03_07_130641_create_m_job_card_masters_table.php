<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMJobCardMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_job_card_masters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_no');
            $table->unsignedInteger('mechanic_id');
//            $table->string('job_no');
            $table->date('mj_card_date');
            $table->tinyInteger('is_loaded_toMI');
            $table->tinyInteger('loaded_MINV');
            $table->timestamps();

            $table->foreign('mechanic_id')->references('id')->on('employees')
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
        Schema::dropIfExists('m_job_card_masters');
    }
}
