<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceJobCardMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_job_card_masters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_no');
            $table->unsignedInteger('vehicle_id');
            $table->dateTime('job_card_date')->nullable();
            $table->tinyInteger('st_card_type')->default(0);
            $table->tinyInteger('is_loaded_to_jo')->default(0);
            $table->unsignedInteger('loaded_job_id')->nullable();
            $table->timestamps();

            /*$table->foreign('vehicle_id')->references('id')->on('vehicles')
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
        Schema::dropIfExists('service_job_card_masters');
    }
}
