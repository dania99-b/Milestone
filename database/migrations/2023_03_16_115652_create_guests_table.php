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
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('education')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('verification_code');
            $table->timestamp('email_verified_at')->nullable();
          //  $table->unsignedBigInteger('question_list_id'); 
          //  $table->foreign('question_list_id')->references('id')->on('question_lists');
        });
    }

    /**
     * Reverse the migrations.
     * 
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guests');
    }
};
