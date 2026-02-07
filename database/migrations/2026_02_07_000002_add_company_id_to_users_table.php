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
        Schema::table('users', function (Blueprint $table) {
            // Add company_id for HQ-level user scoping
            // Users with company_id set are restricted to that HQ's sites
            // Users without company_id (null) are global admins
            $table->foreignUuid('company_id')
                ->nullable()
                ->after('department')
                ->constrained()
                ->onDelete('set null');

            $table->index('company_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
        });
    }
};
