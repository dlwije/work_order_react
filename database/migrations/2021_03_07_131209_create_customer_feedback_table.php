<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_feedback', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('inv_id');
            $table->string('feedback');
            $table->text('feedback_comment');
            $table->timestamps();

            /*$table->foreign('inv_id')->references('id')->on('invoice_masters')
                ->cascadeOnUpdate()->cascadeOnDelete();*/
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_feedback');
    }
}
