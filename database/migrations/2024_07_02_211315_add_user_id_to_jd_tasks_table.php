<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('jd_tasks', function (Blueprint $table) {
            $table->integer('user_id')->after('role');
            $table->integer('is_enable')->default(1)->after('company_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jd_tasks', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('is_enable');
        });
    }
};
