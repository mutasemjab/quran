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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->integer('value')->default(1);
            $table->timestamps();
        });
        DB::table('settings')->insert([
            ['key' => "pray", 'value' => 1],
            ['key' => "athkar_after_pray", 'value' => 1],
            ['key' => "athkar", 'value' => 1],
            ['key' => "asmaa_allah_alhosna", 'value' => 1],
            ['key' => "games", 'value' => 1],
            ['key' => "taqweya", 'value' => 1],
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
};
