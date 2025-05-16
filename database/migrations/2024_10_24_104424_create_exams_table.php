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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., First Exam, Second Exam, Final Exam
            $table->unsignedBigInteger('clas_id'); // Class the exam belongs to
            $table->foreign('clas_id')->references('id')->on('clas')->onDelete('cascade');
            $table->unsignedBigInteger('lesson_id'); 
            $table->foreign('lesson_id')->references('id')->on('lessons')->onDelete('cascade');
            $table->date('exam_date'); // Date of the exam
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
        Schema::dropIfExists('exams');
    }
};
