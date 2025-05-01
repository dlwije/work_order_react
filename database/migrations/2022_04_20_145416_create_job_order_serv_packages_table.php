<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobOrderServPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_order_serv_packages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jo_id')->nullable();
            $table->unsignedBigInteger('service_pkg_id')->nullable();
            $table->string('service_name',199)->default('')->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('vehi_type_id')->nullable();
            $table->double('cost')->default(0.00);
            $table->double('price')->default(0.00);
            $table->double('sub_total')->default(0.00);
            $table->tinyInteger('is_approve_tech')->default(0)->nullable(); // 0=not approved --- stage for technician approve for pkg
            $table->tinyInteger('is_approve_cost')->default(0)->nullable(); // 0=not approved --- stage for customer estimate approve
            $table->tinyInteger('is_approve_work')->default(0)->nullable(); // 0=not approved --- stage for technician work approve
            $table->tinyInteger('jo_serv_pkg_status')->default(0)->nullable(); // 0=just created, 1=approved by serv Mana, 2=assigned labor, 3=work progress, 4=approved work, 5=sent to cashier, 6=completed(paid)
            $table->tinyInteger('is_started')->default(0)->nullable();
            $table->tinyInteger('is_ended')->default(0)->nullable();
            $table->tinyInteger('is_finished')->default(0)->nullable();
            $table->tinyInteger('is_estimate')->default(0)->nullable();
            $table->timestamps();

            $table->foreign('jo_id')->references('id')->on('job_orders')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('service_pkg_id')->references('id')->on('service_packages')
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
        Schema::dropIfExists('job_order_serv_packages');
    }
}
