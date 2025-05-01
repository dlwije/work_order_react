<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sent_from_id')->nullable();
            $table->bigInteger('sent_from_role_id')->nullable();
            $table->bigInteger('sent_to_id')->nullable();
            $table->bigInteger('sent_to_role_id')->nullable();
            $table->string('notify_title',199)->nullable();
            $table->text('notify_body')->nullable();
            $table->timestamp('read_at')->nullable();
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
        Schema::dropIfExists('notifications');
    }
}
