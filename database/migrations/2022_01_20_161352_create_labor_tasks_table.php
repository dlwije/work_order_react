<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaborTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('labor_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('task_code',100)->nullable();
            $table->string('task_name',190)->nullable();
            $table->text('task_description')->nullable();
            $table->unsignedBigInteger('source_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('task_type_id')->nullable();
            $table->double('task_hour')->default(0.0)->nullable();
            $table->double('normal_rate')->default(0.0)->nullable();
            $table->double('warranty_rate')->default(0.0)->nullable();
            $table->tinyInteger('is_active')->default(1)->nullable();
            $table->timestamps();

            $table->foreign('source_id')->references('id')->on('task_sources')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('category_id')->references('id')->on('task_categories')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('task_type_id')->references('id')->on('task_types')
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
        Schema::dropIfExists('labor_tasks');
    }
}
