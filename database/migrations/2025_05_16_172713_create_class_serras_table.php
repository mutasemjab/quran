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
        Schema::create('class_serras', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('clas_id');
            $table->foreign('clas_id')->references('id')->on('clas')->onDelete('cascade');
            $table->unsignedBigInteger('seera_id');
            $table->foreign('seera_id')->references('id')->on('seeras')->onDelete('cascade');
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
        Schema::dropIfExists('class_serras');
    }
};
