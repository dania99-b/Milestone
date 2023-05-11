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
            $table->id();
            $table->timestamp('start_hour')->nullable();
            $table->timestamp('end_hour')->nullable();
            $table->date('start_day')->nullable();
            $table->date('end_day')->nullable();
            $table->json('days');
            $table->string('qr_code')->nullable();
            $table->foreignId('class_id')->constrained('classes');
            $table->foreignId('course_name_id')->constrained('course_names');
            $table->timestamps();
           
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
