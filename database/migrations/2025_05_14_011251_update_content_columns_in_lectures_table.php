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
    public function up(): void
    {
        Schema::table('lectures', function (Blueprint $table) {
            $table->text('content_student')->nullable()->after('content');
            $table->renameColumn('content', 'content_teacher');
        });
    }

    public function down(): void
    {
        Schema::table('lectures', function (Blueprint $table) {
            $table->renameColumn('content_teacher', 'content');
            $table->dropColumn('content_student');
        });
    }
};
