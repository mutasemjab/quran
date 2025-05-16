<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    
    public function up()
    {
        Schema::create('homework_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('homework_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('answer_type'); // 'photo' or 'voice'
            $table->string('file_path'); // Path of the uploaded file
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('homework_answers');
    }
};

