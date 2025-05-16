<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    protected function getForeignKeyName(string $table, string $column): ?string
    {
        $result = DB::selectOne("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_NAME = ?
              AND COLUMN_NAME = ?
              AND CONSTRAINT_SCHEMA = DATABASE()
              AND REFERENCED_TABLE_NAME IS NOT NULL
            LIMIT 1
        ", [$table, $column]);

        return $result->CONSTRAINT_NAME ?? null;
    }

    public function up(): void
    {
        if (Schema::hasColumn('lectures', 'lesson_id')) {
            $fkName = $this->getForeignKeyName('lectures', 'lesson_id');

            if ($fkName) {
                // Drop the FK in a separate statement
                Schema::table('lectures', function (Blueprint $table) use ($fkName) {
                    $table->dropForeign($fkName);
                });
            }

            // Now safely drop the column
            Schema::table('lectures', function (Blueprint $table) {
                $table->dropColumn('lesson_id');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasColumn('lectures', 'lesson_id')) {
            Schema::table('lectures', function (Blueprint $table) {
                $table->unsignedBigInteger('lesson_id')->nullable();
                $table->foreign('lesson_id')->references('id')->on('lessons')->onDelete('cascade');
            });
        }
    }
};
