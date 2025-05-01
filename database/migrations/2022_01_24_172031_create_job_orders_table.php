<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wo_order_id')->nullable(); // this is linked with 'work_orders' table id column
            $table->string('serial_no',190)->nullable();
            $table->string('job_type',10)->nullable();
            $table->date('jo_date')->nullable();
            $table->tinyInteger('jo_status')->default(0); // 0=just created, 1=approved by serv Mana, 2=assigned labor, 3=work progress, 4=approved work, 5=sent to cashier, 6=completed(paid)
            $table->tinyInteger('is_estimate')->default(0)->nullable();
            $table->tinyInteger('is_active')->default(1)->nullable();
            $table->tinyInteger('is_approve_cost')->default(0)->nullable();
            $table->tinyInteger('is_approve_work')->default(0)->nullable();
            $table->timestamps();

            $table->foreign('wo_order_id')->references('id')->on('work_orders')
                ->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_orders');
    }
}
