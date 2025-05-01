<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobOrderTaskTechniciansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_order_task_technicians', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jo_id')->nullable();
            $table->unsignedBigInteger('jo_task_id')->nullable();
            $table->unsignedInteger('tech_id')->nullable();
            $table->timestamps();

            $table->foreign('jo_id')->references('id')->on('job_orders')
                ->cascadeOnUpdate()->cascadeOnDelete();

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
        Schema::dropIfExists('job_order_task_technicians');
    }
}
