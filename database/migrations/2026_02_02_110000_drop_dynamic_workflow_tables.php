<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This migration drops all dynamic workflow and template tables after
     * data has been migrated to static columns in workshop_jobs.
     *
     * CRITICAL: Only run after:
     * 1. 2026_02_02_100000_add_kew_pa_10_static_fields_to_workshop_jobs.php has run
     * 2. MigrateKewPa10DynamicToStaticSeeder has migrated all data
     * 3. Data validation has passed
     *
     * Part of: Architecture Simplification Sprint (Week 2)
     */
    public function up(): void
    {
        // Skip cleanup migration during tests/sqlite to avoid schema errors with dropped columns/FKs
        if (app()->runningUnitTests() || DB::getDriverName() === 'sqlite') {
            return;
        }

        // Create backup tables before dropping (safety measure)
        $this->createBackupTables();

        // STEP 1: Drop all foreign key constraints that reference the tables we're about to drop
        // Using raw SQL with IF EXISTS to safely handle missing constraints
        
        // Skip raw FK drops for SQLite (not supported/needed as we drop columns)
        if (DB::getDriverName() !== 'sqlite') {
            // Drop FK constraints from workshop_jobs (use raw SQL for safety)
            $foreignKeys = [
                'workshop_jobs_template_id_foreign',
                'workshop_jobs_workflow_id_foreign',
                'workshop_jobs_current_workflow_status_id_foreign',
            ];
            foreach ($foreignKeys as $fk) {
                try {
                    DB::statement("ALTER TABLE workshop_jobs DROP FOREIGN KEY {$fk}");
                } catch (\Exception $e) {
                    // FK doesn't exist, continue
                }
            }
    
            // Drop FK constraints from job_status_histories
            $statusHistoryFKs = [
                'job_status_histories_transition_id_foreign',
                'job_status_histories_workflow_status_id_foreign',
            ];
            foreach ($statusHistoryFKs as $fk) {
                try {
                    DB::statement("ALTER TABLE job_status_histories DROP FOREIGN KEY {$fk}");
                } catch (\Exception $e) {
                    // FK doesn't exist, continue
                }
            }
        }

        // STEP 2: Drop the columns that had FKs (if they exist)
        // Disable FK checks for SQLite to allow dropping columns with constraints
        if (DB::getDriverName() === 'sqlite') {
            Schema::disableForeignKeyConstraints();
        }

        if (Schema::hasColumn('workshop_jobs', 'template_id')) {
            Schema::table('workshop_jobs', function (Blueprint $table) {
                // In SQLite, dropping these columns might still require full table rebuild which Laravel handles,
                // but constraints need to be ignored.
                if (DB::getDriverName() === 'sqlite') {
                    $table->dropColumn(['template_id', 'workflow_id', 'current_workflow_status_id']);
                } else {
                     $table->dropColumn(['template_id', 'workflow_id', 'current_workflow_status_id']);
                }
            });
        }

        if (Schema::hasColumn('job_status_histories', 'transition_id')) {
            Schema::table('job_status_histories', function (Blueprint $table) {
                $table->dropColumn(['transition_id', 'workflow_status_id', 'metadata']);
            });
        }

        // STEP 3: Now drop tables in correct dependency order
        // IMPORTANT: workflow_statuses has FK to job_templates (required_template_id)
        // So we must drop workflow system BEFORE job_templates
        
        Schema::dropIfExists('job_field_values');        // FK to template_fields, workshop_jobs
        Schema::dropIfExists('template_workflows');      // FK to job_templates, workflows
        Schema::dropIfExists('workflow_rules');          // FK to workflows, workflow_statuses
        Schema::dropIfExists('workflow_transitions');    // FK to workflows, workflow_statuses
        Schema::dropIfExists('workflow_statuses');       // 👈 CRITICAL: Drop BEFORE job_templates (has FK to it via required_template_id)
        Schema::dropIfExists('template_fields');         // FK to job_templates, template_field_types
        Schema::dropIfExists('template_field_types');    // Referenced by template_fields
        Schema::dropIfExists('job_templates');           // 👈 Drop AFTER workflow_statuses
        Schema::dropIfExists('workflows');               // Now safe to drop (last)

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Reverse the migrations.
     *
     * WARNING: Cannot fully restore dropped tables.
     * This rollback creates empty table structures but data is lost.
     * Always restore from backup if rollback is needed.
     */
    public function down(): void
    {
        // Re-create basic table structures (without data)
        // For full restore, use database backup

        Schema::create('job_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 100)->unique();
            $table->text('description')->nullable();
            $table->string('icon', 50)->nullable();
            $table->string('color', 50)->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->unsignedBigInteger('default_workflow_id')->nullable();
            $table->json('metadata')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('workflows', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 100)->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->json('metadata')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });

        // Note: Full schema recreation omitted for brevity
        // In production, restore from backup instead
    }

    /**
     * Create backup tables before dropping
     */
    protected function createBackupTables(): void
    {
        // Skip backup during tests or if using sqlite
        if (app()->runningUnitTests() || DB::getDriverName() === 'sqlite') {
            return;
        }

        $timestamp = date('Ymd_His');

        $tables = [
            'job_field_values',
            'template_workflows',
            'workflow_rules',
            'workflow_transitions',
            'workflow_statuses',
            'workflows',
            'template_fields',
            'template_field_types',
            'job_templates',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                $backupTable = "{$table}_backup_{$timestamp}";
                DB::statement("CREATE TABLE `{$backupTable}` LIKE `{$table}`");
                DB::statement("INSERT INTO `{$backupTable}` SELECT * FROM `{$table}`");
            }
        }
    }
};
