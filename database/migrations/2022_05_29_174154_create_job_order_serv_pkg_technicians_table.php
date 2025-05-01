<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobOrderServPkgTechniciansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_order_serv_pkg_technicians', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('jo_id')->nullable();
            $table->bigInteger('jo_serv_pkg_id')->nullable();
            $table->bigInteger('tech_id')->nullable();
            $table->timestamps();

            /*$table->foreign('jo_id')->references('id')->on('job_orders')
                ->cascadeOnUpdate()->cascadeOnDelete();

            $table->foreign('jo_serv_pkg_id')->references('id')->on('job_order_serv_packages')
                ->cascadeOnUpdate()->cascadeOnDelete();

            $table->foreign('tech_id')->references('id')->on('employees')
                ->cascadeOnDelete()->cascadeOnUpdate();*/
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_order_serv_pkg_technicians');
    }
}
