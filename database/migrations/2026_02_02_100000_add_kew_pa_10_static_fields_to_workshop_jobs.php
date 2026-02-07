<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This migration adds static columns to workshop_jobs table for KEW.PA-10 jobs,
     * replacing the dynamic EAV (Entity-Attribute-Value) model with direct columns.
     *
     * Part of: Architecture Simplification Sprint (Week 2)
     * Replaces: job_field_values table for KEW.PA-10 template fields
     */
    public function up(): void
    {
        Schema::table('workshop_jobs', function (Blueprint $table) {
            // Job Mode - Determines which fields are used
            $table->enum('job_mode', ['KEW_PA_10', 'NORMAL'])
                ->after('id')
                ->default('NORMAL')
                ->comment('Job type: KEW.PA-10 (government) or NORMAL (standard workshop)');

            // -------------------------------------------
            // KEW.PA-10 Static Fields
            // All nullable (only used when job_mode = 'KEW_PA_10')
            // -------------------------------------------

            // 1. KEW.PA-10 Number (required for KEW jobs)
            $table->string('kew_pa_10_number', 50)
                ->nullable()
                ->after('job_mode')
                ->comment('KEW.PA-10 form number (e.g., KEW.PA-10/2026/001)');

            // 2. Received Date (required for KEW jobs)
            $table->date('kew_pa_10_received_date')
                ->nullable()
                ->after('kew_pa_10_number')
                ->comment('Date when KEW.PA-10 form was received');

            // 3. Government Department (FK - required for KEW jobs)
            $table->foreignId('kew_pa_10_government_department_id')
                ->nullable()
                ->after('kew_pa_10_received_date')
                ->constrained('government_departments')
                ->onDelete('set null')
                ->comment('Government department submitting the request');

            // 4. Asset (FK - required for KEW jobs)
            $table->foreignId('kew_pa_10_asset_id')
                ->nullable()
                ->after('kew_pa_10_government_department_id')
                ->constrained('assets')
                ->onDelete('set null')
                ->comment('Asset to be repaired or disposed');

            // 5. Description (required for KEW jobs)
            $table->text('kew_pa_10_description')
                ->nullable()
                ->after('kew_pa_10_asset_id')
                ->comment('Detailed description of repair/disposal work needed');

            // 6. Priority (required for KEW jobs)
            $table->enum('kew_pa_10_priority', ['low', 'medium', 'high', 'urgent'])
                ->nullable()
                ->default('medium')
                ->after('kew_pa_10_description')
                ->comment('Priority level for KEW.PA-10 job');

            // 7. Budget Allocation Reference (optional)
            $table->string('kew_pa_10_budget_reference', 100)
                ->nullable()
                ->after('kew_pa_10_priority')
                ->comment('Budget allocation reference (e.g., VOT 13000)');

            // 8. Document Path (optional - PDF upload)
            $table->string('kew_pa_10_document_path', 255)
                ->nullable()
                ->after('kew_pa_10_budget_reference')
                ->comment('Path to uploaded KEW.PA-10 PDF document');

            // 9. Form Completeness Verified (optional - checkbox)
            $table->boolean('kew_pa_10_form_verified')
                ->nullable()
                ->default(false)
                ->after('kew_pa_10_document_path')
                ->comment('Whether the form has been verified as complete');

            // 10. Signatures Verified (optional - checkbox)
            $table->boolean('kew_pa_10_signatures_verified')
                ->nullable()
                ->default(false)
                ->after('kew_pa_10_form_verified')
                ->comment('Whether all signatures have been verified');

            // -------------------------------------------
            // Indexes for Performance
            // -------------------------------------------

            $table->index('job_mode', 'idx_job_mode');
            $table->index('kew_pa_10_number', 'idx_kew_pa_10_number');
            $table->index('kew_pa_10_received_date', 'idx_kew_received_date');
            $table->index('kew_pa_10_priority', 'idx_kew_priority');
        });
    }

    /**
     * Reverse the migrations.
     *
     * WARNING: This will remove all KEW.PA-10 static fields.
     * Ensure backup exists before running rollback.
     */
    public function down(): void
    {
        Schema::table('workshop_jobs', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['kew_pa_10_government_department_id']);
            $table->dropForeign(['kew_pa_10_asset_id']);

            // Drop indexes
            $table->dropIndex('idx_job_mode');
            $table->dropIndex('idx_kew_pa_10_number');
            $table->dropIndex('idx_kew_received_date');
            $table->dropIndex('idx_kew_priority');

            // Drop columns
            $table->dropColumn([
                'job_mode',
                'kew_pa_10_number',
                'kew_pa_10_received_date',
                'kew_pa_10_government_department_id',
                'kew_pa_10_asset_id',
                'kew_pa_10_description',
                'kew_pa_10_priority',
                'kew_pa_10_budget_reference',
                'kew_pa_10_document_path',
                'kew_pa_10_form_verified',
                'kew_pa_10_signatures_verified',
            ]);
        });
    }
};
