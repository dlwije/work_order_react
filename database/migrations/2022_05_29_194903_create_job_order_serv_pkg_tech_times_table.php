<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobOrderServPkgTechTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_order_serv_pkg_tech_times', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('jo_serv_pkg_id')->nullable();
            $table->unsignedInteger('tech_id')->nullable();
            $table->date('date')->nullable();
            $table->dateTime('start_datetime')->nullable();
            $table->dateTime('end_datetime')->nullable();
            $table->double('session_tot_time')->default(0.0)->nullable();
            $table->tinyInteger('is_started')->default(0)->nullable();
            $table->tinyInteger('is_ended')->default(0)->nullable();
            $table->tinyInteger('is_finished')->default(0)->nullable();
            $table->timestamps();

            $table->foreign('tech_id')->references('id')->on('employees')
                ->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_order_serv_pkg_tech_times');
    }
}
