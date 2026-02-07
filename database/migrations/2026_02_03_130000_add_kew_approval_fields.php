<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds columns to track KEW.PA-10 approval status and audit trail.
     */
    public function up(): void
    {
        Schema::table('workshop_jobs', function (Blueprint $table) {
            $table->string('kew_approval_status')->nullable()->after('kew_pa_10_signatures_verified');
            $table->foreignId('kew_approved_by_id')->nullable()->after('kew_approval_status')->constrained('users');
            $table->dateTime('kew_approved_at')->nullable()->after('kew_approved_by_id');
            $table->text('kew_rejection_reason')->nullable()->after('kew_approved_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workshop_jobs', function (Blueprint $table) {
            $table->dropForeign(['kew_approved_by_id']);
            $table->dropColumn([
                'kew_approval_status',
                'kew_approved_by_id',
                'kew_approved_at',
                'kew_rejection_reason',
            ]);
        });
    }
};
