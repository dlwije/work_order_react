<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobOrderTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_order_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jo_id')->nullable();
            $table->unsignedBigInteger('task_id')->nullable();
            $table->string('task_name',190)->nullable();
            $table->text('task_description')->nullable();
            $table->double('hours')->default(0.0)->nullable();
            $table->double('hourly_rate')->default(0.0)->nullable();
            $table->double('actual_hours')->default(0.0)->nullable();
            $table->double('labor_cost')->default(0.0)->nullable();
            $table->double('total')->default(0.0)->nullable();
            $table->tinyInteger('is_approve_cost')->default(0)->nullable(); // 0=not approved --- stage for customer estimate approve
            $table->tinyInteger('is_approve_work')->default(0)->nullable(); // 0=not approved --- stage for technician work approve
            $table->tinyInteger('jo_tsk_status')->default(0)->nullable(); // 0=just created, 1=approved by serv Mana, 2=assigned labor, 3=work progress, 4=approved work, 5=sent to cashier, 6=completed(paid)
            $table->tinyInteger('is_started')->default(0)->nullable();
            $table->tinyInteger('is_ended')->default(0)->nullable();
            $table->tinyInteger('is_finished')->default(0)->nullable();
            $table->tinyInteger('is_estimate')->default(0)->nullable();
            $table->timestamps();

            $table->foreign('jo_id')->references('id')->on('job_orders')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('task_id')->references('id')->on('labor_tasks')
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
        Schema::dropIfExists('job_order_tasks');
    }
}
