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
        Schema::create('guest_question_lists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('guest_id');
            $table->unsignedBigInteger('question_list_id');
            $table->primary(['guest_id', 'question_list_id']);
     

            // Add any additional columns to the pivot table as needed

            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('guest_id')->references('id')->on('guests')->onDelete('cascade');
            $table->foreign('question_list_id')->references('id')->on('question_lists')->onDelete('cascade');
            $table->string('answer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guest_question_lists');
    }
};
