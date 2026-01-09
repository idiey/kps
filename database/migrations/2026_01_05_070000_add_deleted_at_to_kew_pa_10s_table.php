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
        if (!Schema::hasColumn('kew_pa_10s', 'deleted_at')) {
            Schema::table('kew_pa_10s', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('kew_pa_10s', 'deleted_at')) {
            Schema::table('kew_pa_10s', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
};
