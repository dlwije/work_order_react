<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateSmaPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sma_pages', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('name', 15);
            $table->string('title', 60);
            $table->string('description', 180);
            $table->string('slug', 55)->nullable();
            $table->text('body');
            $table->tinyInteger('active');
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->tinyInteger('order_no')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sma_pages');
    }
}
