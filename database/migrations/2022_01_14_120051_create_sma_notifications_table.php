<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateSmaNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sma_notifications', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->text('comment');
            $table->timestamp('date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('from_date')->nullable();
            $table->dateTime('till_date')->nullable();
            $table->tinyInteger('scope')->default(3);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sma_notifications');
    }
}
