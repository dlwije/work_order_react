<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmaCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sma_categories', function (Blueprint $table) {
            $table->integer('id')->index()->autoIncrement();
            $table->string('code', 55);
            $table->string('name', 55);
            $table->string('image', 55)->nullable();
            $table->integer('parent_id')->nullable();
            $table->string('slug', 55)->nullable();
            $table->string('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sma_categories');
    }
}
