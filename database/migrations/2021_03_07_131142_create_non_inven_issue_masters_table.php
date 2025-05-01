<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNonInvenIssueMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('non_inven_issue_masters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_no');
            $table->unsignedInteger('employee_id');
            $table->date('issue_date');
            $table->double('tot_qty');
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')
            ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('non_inven_issue_masters');
    }
}
