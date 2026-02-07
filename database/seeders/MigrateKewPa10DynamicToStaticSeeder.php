<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MigrateKewPa10DynamicToStaticSeeder extends Seeder
{
    /**
     * Migrate KEW.PA-10 dynamic field values to static columns.
     *
     * This seeder runs after the column migration to copy data from:
     * - job_field_values (dynamic EAV model)
     * TO:
     * - workshop_jobs (static columns)
     *
     * Prerequisites:
     * - Migration 2026_02_02_100000_add_kew_pa_10_static_fields_to_workshop_jobs must be run first
     * - job_field_values table must still exist
     *
     * Part of: Architecture Simplification Sprint (Week 2)
     */
    public function run(): void
    {
        $this->command->info('Starting KEW.PA-10 dynamic → static migration...');

        DB::beginTransaction();

        try {
            // Step 1: Set job_mode for all jobs
            $this->setJobModes();

            // Step 2: Migrate each field
            $this->migrateKewPa10Number();
            $this->migrateDateReceived();
            $this->migrateGovernmentDepartment();
            $this->migrateAsset();
            $this->migrateDescription();
            $this->migratePriority();
            $this->migrateBudgetReference();
            $this->migrateDocumentPath();
            $this->migrateFormVerified();
            $this->migrateSignaturesVerified();

            // Step 3: Validate migration
            $this->validateMigration();

            DB::commit();

            $this->command->info('✅ KEW.PA-10 data migration completed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('❌ Migration failed: ' . $e->getMessage());
            Log::error('KEW.PA-10 migration failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            throw $e;
        }
    }

    /**
     * Set job_mode based on template
     */
    protected function setJobModes(): void
    {
        $this->command->info('Setting job modes...');

        // Get KEW.PA-10 template ID
        $kewTemplateId = DB::table('job_templates')
            ->where('code', 'kew-pa-10')
            ->value('id');

        if (!$kewTemplateId) {
            $this->command->warn('⚠️  KEW.PA-10 template not found - skipping job mode assignment');
            return;
        }

        // Set KEW.PA-10 jobs
        $kewCount = DB::table('workshop_jobs')
            ->where('template_id', $kewTemplateId)
            ->update(['job_mode' => 'KEW_PA_10']);

        // Set NORMAL jobs
        $normalCount = DB::table('workshop_jobs')
            ->where(function ($q) use ($kewTemplateId) {
                $q->whereNull('template_id')
                    ->orWhere('template_id', '!=', $kewTemplateId);
            })
            ->update(['job_mode' => 'NORMAL']);

        $this->command->info("  ✓ Set {$kewCount} jobs to KEW_PA_10");
        $this->command->info("  ✓ Set {$normalCount} jobs to NORMAL");
    }

    /**
     * Migrate KEW.PA-10 number
     */
    protected function migrateKewPa10Number(): void
    {
        $this->command->info('Migrating KEW.PA-10 numbers...');

        $count = DB::statement("
            UPDATE workshop_jobs wj
            JOIN job_field_values jfv ON wj.id = jfv.job_id
            JOIN template_fields tf ON jfv.field_id = tf.id
            SET wj.kew_pa_10_number = jfv.value_text
            WHERE tf.code = 'kew_pa_10_number'
            AND wj.job_mode = 'KEW_PA_10'
        ");

        $this->command->info("  ✓ Migrated {$count} KEW.PA-10 numbers");
    }

    /**
     * Migrate received date
     */
    protected function migrateDateReceived(): void
    {
        $this->command->info('Migrating received dates...');

        $count = DB::statement("
            UPDATE workshop_jobs wj
            JOIN job_field_values jfv ON wj.id = jfv.job_id
            JOIN template_fields tf ON jfv.field_id = tf.id
            SET wj.kew_pa_10_received_date = jfv.value_date
            WHERE tf.code = 'received_date'
            AND wj.job_mode = 'KEW_PA_10'
        ");

        $this->command->info("  ✓ Migrated {$count} received dates");
    }

    /**
     * Migrate government department FK
     */
    protected function migrateGovernmentDepartment(): void
    {
        $this->command->info('Migrating government departments...');

        $count = DB::statement("
            UPDATE workshop_jobs wj
            JOIN job_field_values jfv ON wj.id = jfv.job_id
            JOIN template_fields tf ON jfv.field_id = tf.id
            SET wj.kew_pa_10_government_department_id = CAST(jfv.value_number AS UNSIGNED)
            WHERE tf.code = 'government_department_id'
            AND wj.job_mode = 'KEW_PA_10'
            AND jfv.value_number IS NOT NULL
        ");

        $this->command->info("  ✓ Migrated {$count} government department references");
    }

    /**
     * Migrate asset FK
     */
    protected function migrateAsset(): void
    {
        $this->command->info('Migrating assets...');

        $count = DB::statement("
            UPDATE workshop_jobs wj
            JOIN job_field_values jfv ON wj.id = jfv.job_id
            JOIN template_fields tf ON jfv.field_id = tf.id
            SET wj.kew_pa_10_asset_id = CAST(jfv.value_number AS UNSIGNED)
            WHERE tf.code = 'asset_id'
            AND wj.job_mode = 'KEW_PA_10'
            AND jfv.value_number IS NOT NULL
        ");

        $this->command->info("  ✓ Migrated {$count} asset references");
    }

    /**
     * Migrate description (perihal pembaikan)
     */
    protected function migrateDescription(): void
    {
        $this->command->info('Migrating descriptions...');

        $count = DB::statement("
            UPDATE workshop_jobs wj
            JOIN job_field_values jfv ON wj.id = jfv.job_id
            JOIN template_fields tf ON jfv.field_id = tf.id
            SET wj.kew_pa_10_description = jfv.value_text
            WHERE tf.code = 'description'
            AND wj.job_mode = 'KEW_PA_10'
        ");

        $this->command->info("  ✓ Migrated {$count} descriptions");
    }

    /**
     * Migrate priority
     */
    protected function migratePriority(): void
    {
        $this->command->info('Migrating priorities...');

        $count = DB::statement("
            UPDATE workshop_jobs wj
            JOIN job_field_values jfv ON wj.id = jfv.job_id
            JOIN template_fields tf ON jfv.field_id = tf.id
            SET wj.kew_pa_10_priority = jfv.value_text
            WHERE tf.code = 'priority'
            AND wj.job_mode = 'KEW_PA_10'
            AND jfv.value_text IN ('low', 'medium', 'high', 'urgent')
        ");

        $this->command->info("  ✓ Migrated {$count} priorities");
    }

    /**
     * Migrate budget allocation reference
     */
    protected function migrateBudgetReference(): void
    {
        $this->command->info('Migrating budget references...');

        $count = DB::statement("
            UPDATE workshop_jobs wj
            JOIN job_field_values jfv ON wj.id = jfv.job_id
            JOIN template_fields tf ON jfv.field_id = tf.id
            SET wj.kew_pa_10_budget_reference = jfv.value_text
            WHERE tf.code = 'budget_allocation_reference'
            AND wj.job_mode = 'KEW_PA_10'
        ");

        $this->command->info("  ✓ Migrated {$count} budget references");
    }

    /**
     * Migrate document path
     */
    protected function migrateDocumentPath(): void
    {
        $this->command->info('Migrating document paths...');

        $count = DB::statement("
            UPDATE workshop_jobs wj
            JOIN job_field_values jfv ON wj.id = jfv.job_id
            JOIN template_fields tf ON jfv.field_id = tf.id
            SET wj.kew_pa_10_document_path = jfv.value_text
            WHERE tf.code = 'kew_pa_10_document'
            AND wj.job_mode = 'KEW_PA_10'
        ");

        $this->command->info("  ✓ Migrated {$count} document paths");
    }

    /**
     * Migrate form verified checkbox
     */
    protected function migrateFormVerified(): void
    {
        $this->command->info('Migrating form verification flags...');

        $count = DB::statement("
            UPDATE workshop_jobs wj
            JOIN job_field_values jfv ON wj.id = jfv.job_id
            JOIN template_fields tf ON jfv.field_id = tf.id
            SET wj.kew_pa_10_form_verified = COALESCE(jfv.value_boolean, 0)
            WHERE tf.code = 'form_completeness_verified'
            AND wj.job_mode = 'KEW_PA_10'
        ");

        $this->command->info("  ✓ Migrated {$count} form verification flags");
    }

    /**
     * Migrate signatures verified checkbox
     */
    protected function migrateSignaturesVerified(): void
    {
        $this->command->info('Migrating signature verification flags...');

        $count = DB::statement("
            UPDATE workshop_jobs wj
            JOIN job_field_values jfv ON wj.id = jfv.job_id
            JOIN template_fields tf ON jfv.field_id = tf.id
            SET wj.kew_pa_10_signatures_verified = COALESCE(jfv.value_boolean, 0)
            WHERE tf.code = 'signatures_verified'
            AND wj.job_mode = 'KEW_PA_10'
        ");

        $this->command->info("  ✓ Migrated {$count} signature verification flags");
    }

    /**
     * Validate migration results
     */
    protected function validateMigration(): void
    {
        $this->command->info('Validating migration...');

        // Count KEW.PA-10 jobs
        $totalKewJobs = DB::table('workshop_jobs')
            ->where('job_mode', 'KEW_PA_10')
            ->count();

        // Count jobs with required fields
        $withNumber = DB::table('workshop_jobs')
            ->where('job_mode', 'KEW_PA_10')
            ->whereNotNull('kew_pa_10_number')
            ->count();

        $withDate = DB::table('workshop_jobs')
            ->where('job_mode', 'KEW_PA_10')
            ->whereNotNull('kew_pa_10_received_date')
            ->count();

        $withDescription = DB::table('workshop_jobs')
            ->where('job_mode', 'KEW_PA_10')
            ->whereNotNull('kew_pa_10_description')
            ->count();

        $this->command->info("Validation Results:");
        $this->command->info("  Total KEW.PA-10 jobs: {$totalKewJobs}");
        $this->command->info("  With KEW number: {$withNumber}");
        $this->command->info("  With received date: {$withDate}");
        $this->command->info("  With description: {$withDescription}");

        // Check for data integrity issues
        if ($totalKewJobs > 0) {
            $missingNumber = $totalKewJobs - $withNumber;
            $missingDate = $totalKewJobs - $withDate;
            $missingDescription = $totalKewJobs - $withDescription;

            if ($missingNumber > 0) {
                $this->command->warn("  ⚠️  {$missingNumber} jobs missing KEW number");
            }
            if ($missingDate > 0) {
                $this->command->warn("  ⚠️  {$missingDate} jobs missing received date");
            }
            if ($missingDescription > 0) {
                $this->command->warn("  ⚠️  {$missingDescription} jobs missing description");
            }

            if ($missingNumber === 0 && $missingDate === 0 && $missingDescription === 0) {
                $this->command->info("  ✅ All required fields populated!");
            }
        }
    }
}
