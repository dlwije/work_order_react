<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('sale_id')->index('sale_id');
            $table->unsignedBigInteger('jo_id')->nullable();
            $table->unsignedBigInteger('task_id')->nullable();
            $table->string('task_name',190)->nullable();
            $table->text('task_description')->nullable();
            $table->double('hours')->default(0.0)->nullable();
            $table->double('hourly_rate')->default(0.0)->nullable();
            $table->double('actual_hours')->default(0.0)->nullable();
            $table->double('labor_cost')->default(0.0)->nullable();
            $table->double('total')->default(0.0)->nullable();
            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_tasks');
    }
}
