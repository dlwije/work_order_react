<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateServiceJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_jobs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_no',15);
            $table->unsignedInteger('customer_id')->nullable();
            $table->string('customer_name')->nullable();
            $table->char('contact_no',15)->nullable();
            $table->text('address')->nullable();
            $table->unsignedInteger('vehicle_id')->nullable();
            $table->string('vehicle_no')->nullable();
            $table->string('vehicle_make')->nullable();
            $table->string('vehicle_model')->nullable();
            $table->string('chassis_no')->nullable();
            $table->string('engine_no')->nullable();
            $table->unsignedInteger('vehicle_type_id')->nullable();
            $table->unsignedInteger('appointment_id')->nullable();
            $table->integer('mileage')->default(0);
            $table->dateTime('estimated_time')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->text('report_defects')->nullable();
            $table->integer('job_status')->nullable();
            $table->unsignedInteger('service_adviser')->nullable();
            $table->integer('job_type')->default(0);
            $table->timestamps();

            /*$table->foreign('customer_id')->references('id')->on('customers')
                ->cascadeOnUpdate();
            $table->foreign('vehicle_id')->references('id')->on('vehicles')
                ->cascadeOnUpdate();
            $table->foreign('vehicle_type_id')->references('id')->on('vehicle_types')
                ->cascadeOnUpdate();
            $table->foreign('service_adviser')->references('id')->on('employees')
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
        Schema::dropIfExists('service_jobs');
    }
}
