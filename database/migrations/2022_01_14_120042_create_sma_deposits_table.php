<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateSmaDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sma_deposits', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->timestamp('date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('company_id');
            $table->decimal('amount', 25, 4);
            $table->string('paid_by', 50)->nullable();
            $table->string('note')->nullable();
            $table->integer('created_by');
            $table->char('sys_type',4)->nullable();
            $table->integer('updated_by');
            $table->dateTime('updated_at')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sma_deposits');
    }
}
