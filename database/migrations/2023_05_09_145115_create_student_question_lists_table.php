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
        Schema::create('student_question_lists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('question_list_id');
            $table->primary(['student_id', 'question_list_id']);
            
     

            // Add any additional columns to the pivot table as needed

            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('question_list_id')->references('id')->on('question_lists')->onDelete('cascade');
            $table->unsignedBigInteger('answer_id'); 
            $table->foreign('answer_id')->references('id')->on('answers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_question_lists');
    }
};
