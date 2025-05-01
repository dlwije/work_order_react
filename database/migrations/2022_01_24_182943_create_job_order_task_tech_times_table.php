<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobOrderTaskTechTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_order_task_tech_times', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jo_task_id')->nullable();
            $table->unsignedInteger('tech_id')->nullable();
            $table->date('date')->nullable();
            $table->dateTime('start_datetime')->nullable();
            $table->dateTime('end_datetime')->nullable();
            $table->time('session_tot_time')->default('00:00:00')->nullable();
            $table->tinyInteger('is_started')->default(0)->nullable();
            $table->tinyInteger('is_ended')->default(0)->nullable();
            $table->tinyInteger('is_finished')->default(0)->nullable();
            $table->timestamps();

            $table->foreign('jo_task_id')->references('id')->on('job_order_tasks')
                ->cascadeOnUpdate()->cascadeOnDelete();

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
        Schema::dropIfExists('job_order_task_tech_times');
    }
}
