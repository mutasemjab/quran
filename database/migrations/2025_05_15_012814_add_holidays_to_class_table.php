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
        Schema::table('clas', function (Blueprint $table) {
            //
            if (!Schema::hasColumn('clas', 'holidays')) {
                Schema::table('clas', function (Blueprint $table) {
                    $table->json('holidays')->nullable()->after('name');
                });
            }
        });
    }

    /**

     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clas', function (Blueprint $table) {
            //
            if (Schema::hasColumn('clas', 'holidays')) {
                Schema::table('clas', function (Blueprint $table) {
                    $table->dropColumn('holidays');
                });
            }
        });
    }
};
