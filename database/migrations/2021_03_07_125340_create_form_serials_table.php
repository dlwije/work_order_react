<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateFormSerialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_serials', function (Blueprint $table) {
            $table->increments('id');
            $table->string('prefix',190)->nullable();
            $table->string('form');
            $table->integer('num')->default('00001');
            $table->timestamps();
        });
        DB::statement('ALTER TABLE form_serials CHANGE num num INT(5) UNSIGNED ZEROFILL NOT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_serials');
    }
}
