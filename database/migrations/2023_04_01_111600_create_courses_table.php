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
        Schema::create('courses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('course_name');
            $table->unsignedBigInteger('class_id'); 
            $table->foreign('class_id')->references('id')->on('classes');
            $table->timestamp('start_hour')->nullable();
            $table->timestamp('end_hour')->nullable();
            $table->date('start_day')->nullable();
            $table->date('end_day')->nullable();
            $table->string('status')->nullable();
            $table->string('qr_code')->nullable();
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
        Schema::dropIfExists('courses');
    }
};
