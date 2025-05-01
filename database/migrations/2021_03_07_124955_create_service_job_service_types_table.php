<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceJobServiceTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_job_service_types', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('serv_job_id')->nullable();
            $table->unsignedInteger('t_of_service_id')->nullable();
            $table->timestamps();

            $table->foreign('serv_job_id')->references('id')->on('service_jobs')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('t_of_service_id')->references('id')->on('type_of_services')
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
        Schema::dropIfExists('service_job_service_types');
    }
}
