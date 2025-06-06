<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCusRemindersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cus_reminders', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('serv_job_id')->nullable();
            $table->unsignedInteger('customer_id')->nullable();
            $table->dateTime('removed_on')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->unsignedInteger('remove_user_id')->nullable();
            $table->timestamps();

            $table->foreign('serv_job_id')->references('id')->on('service_jobs')
                ->cascadeOnUpdate()->cascadeOnDelete();
            /*$table->foreign('customer_id')->references('id')->on('customers')
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
        Schema::dropIfExists('cus_reminders');
    }
}
