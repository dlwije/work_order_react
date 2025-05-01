<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskTechCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_tech_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('tech_id')->nullable();
            $table->unsignedBigInteger('jo_id')->nullable();
            $table->unsignedBigInteger('jo_task_id')->nullable();
            $table->longText('message')->nullable();
            $table->tinyInteger('msg_state')->default(0);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('user_role_id')->nullable();
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
        Schema::dropIfExists('task_tech_comments');
    }
}
