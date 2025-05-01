<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmaUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sma_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('last_ip_address', 45)->nullable();
            $table->string('ip_address', 45);
            $table->string('username', 100);
            $table->string('password', 40);
            $table->string('salt', 40)->nullable();
            $table->string('email', 100);
            $table->string('activation_code', 40)->nullable();
            $table->string('forgotten_password_code', 40)->nullable();
            $table->unsignedInteger('forgotten_password_time')->nullable();
            $table->string('remember_code', 40)->nullable();
            $table->unsignedInteger('created_on');
            $table->unsignedInteger('last_login')->nullable();
            $table->unsignedTinyInteger('active')->nullable();
            $table->string('first_name', 50)->nullable();
            $table->string('last_name', 50)->nullable();
            $table->string('company', 100)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('avatar', 55)->nullable();
            $table->string('gender', 20)->nullable();
            $table->unsignedInteger('group_id');
            $table->unsignedInteger('warehouse_id')->nullable();
            $table->unsignedInteger('biller_id')->nullable();
            $table->integer('company_id')->nullable();
            $table->boolean('show_cost')->default(0);
            $table->boolean('show_price')->default(0);
            $table->integer('award_points')->default(0);
            $table->boolean('view_right')->default(0);
            $table->boolean('edit_right')->default(0);
            $table->boolean('allow_discount')->default(0);
            $table->unsignedBigInteger('wo_user_id');

            $table->index(['group_id', 'warehouse_id', 'biller_id'], 'group_id');
            $table->index(['group_id', 'company_id'], 'group_id_2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sma_users');
    }
}
