<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_placements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('student_id'); 
            $table->foreign('student_id')->references('id')->on('students');
            $table->unsignedBigInteger('question_list_id'); 
            $table->foreign('question_list_id')->references('id')->on('question_lists');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_placements');
    }
};
