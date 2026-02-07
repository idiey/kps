<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Add database indexes for performance optimization.
     * Week 5: Production Prep
     */
    public function up(): void
    {
        // Helper function to check if index exists
        $hasIndex = function ($table, $indexName) {
            try {
                $indexes = DB::select("SHOW INDEX FROM {$table} WHERE Key_name = ?", [$indexName]);
                return count($indexes) > 0;
            } catch (\Exception $e) {
                return false;
            }
        };

        // Workshop Jobs - Primary query patterns
        Schema::table('workshop_jobs', function (Blueprint $table) use ($hasIndex) {
            if (!$hasIndex('workshop_jobs', 'idx_jobs_mode')) {
                $table->index('job_mode', 'idx_jobs_mode');
            }
            if (!$hasIndex('workshop_jobs', 'idx_jobs_status_mode')) {
                $table->index(['status', 'job_mode'], 'idx_jobs_status_mode');
            }
            if (!$hasIndex('workshop_jobs', 'idx_jobs_created')) {
                $table->index('created_at', 'idx_jobs_created');
            }
            if (!$hasIndex('workshop_jobs', 'idx_jobs_assigned')) {
                $table->index('assigned_to', 'idx_jobs_assigned');
            }
            if (!$hasIndex('workshop_jobs', 'idx_jobs_kew_approved_by')) {
                $table->index('kew_approved_by_id', 'idx_jobs_kew_approved_by');
            }
        });

        // Job Status Histories - Audit trail queries
        Schema::table('job_status_histories', function (Blueprint $table) use ($hasIndex) {
            if (!$hasIndex('job_status_histories', 'idx_history_job')) {
                $table->index('workshop_job_id', 'idx_history_job');
            }
            if (!$hasIndex('job_status_histories', 'idx_history_user')) {
                $table->index('user_id', 'idx_history_user');
            }
            if (!$hasIndex('job_status_histories', 'idx_history_date')) {
                $table->index('changed_at', 'idx_history_date');
            }
        });

        // Customers - Search patterns
        Schema::table('customers', function (Blueprint $table) use ($hasIndex) {
            if (!$hasIndex('customers', 'idx_customers_type')) {
                $table->index('customer_type', 'idx_customers_type');
            }
        });

        // Parts - Inventory queries (only if table exists)
        if (Schema::hasTable('parts')) {
            Schema::table('parts', function (Blueprint $table) use ($hasIndex) {
                if (!$hasIndex('parts', 'idx_parts_category')) {
                    $table->index('category', 'idx_parts_category');
                }
                if (!$hasIndex('parts', 'idx_parts_low_stock')) {
                    $table->index(['quantity_in_stock', 'minimum_stock_level'], 'idx_parts_low_stock');
                }
            });
        }

        // Stock Movements - Audit trail (only if table exists)
        if (Schema::hasTable('stock_movements')) {
            Schema::table('stock_movements', function (Blueprint $table) use ($hasIndex) {
                if (!$hasIndex('stock_movements', 'idx_movements_part_date')) {
                    $table->index(['part_id', 'created_at'], 'idx_movements_part_date');
                }
            });
        }

        // Assets - Management queries  
        Schema::table('assets', function (Blueprint $table) use ($hasIndex) {
            if (!$hasIndex('assets', 'idx_assets_type')) {
                $table->index('asset_type', 'idx_assets_type');
            }
            if (!$hasIndex('assets', 'idx_assets_condition')) {
                $table->index('current_condition', 'idx_assets_condition');
            }
            if (!$hasIndex('assets', 'idx_assets_department')) {
                $table->index('government_department_id', 'idx_assets_department');
            }
        });

        // Settings - Lookup optimization (only if table exists)
        if (Schema::hasTable('settings')) {
            Schema::table('settings', function (Blueprint $table) use ($hasIndex) {
                if (!$hasIndex('settings', 'idx_settings_group')) {
                    $table->index('group', 'idx_settings_group');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $hasIndex = function ($table, $indexName) {
            try {
                $indexes = DB::select("SHOW INDEX FROM {$table} WHERE Key_name = ?", [$indexName]);
                return count($indexes) > 0;
            } catch (\Exception $e) {
                return false;
            }
        };

        Schema::table('workshop_jobs', function (Blueprint $table) use ($hasIndex) {
            if ($hasIndex('workshop_jobs', 'idx_jobs_mode')) $table->dropIndex('idx_jobs_mode');
            if ($hasIndex('workshop_jobs', 'idx_jobs_status_mode')) $table->dropIndex('idx_jobs_status_mode');
            if ($hasIndex('workshop_jobs', 'idx_jobs_created')) $table->dropIndex('idx_jobs_created');
            if ($hasIndex('workshop_jobs', 'idx_jobs_assigned')) $table->dropIndex('idx_jobs_assigned');
            if ($hasIndex('workshop_jobs', 'idx_jobs_kew_approved_by')) $table->dropIndex('idx_jobs_kew_approved_by');
        });

        Schema::table('job_status_histories', function (Blueprint $table) use ($hasIndex) {
            if ($hasIndex('job_status_histories', 'idx_history_job')) $table->dropIndex('idx_history_job');
            if ($hasIndex('job_status_histories', 'idx_history_user')) $table->dropIndex('idx_history_user');
            if ($hasIndex('job_status_histories', 'idx_history_date')) $table->dropIndex('idx_history_date');
        });

        Schema::table('customers', function (Blueprint $table) use ($hasIndex) {
            if ($hasIndex('customers', 'idx_customers_type')) $table->dropIndex('idx_customers_type');
        });

        if (Schema::hasTable('parts')) {
            Schema::table('parts', function (Blueprint $table) use ($hasIndex) {
                if ($hasIndex('parts', 'idx_parts_category')) $table->dropIndex('idx_parts_category');
                if ($hasIndex('parts', 'idx_parts_low_stock')) $table->dropIndex('idx_parts_low_stock');
            });
        }

        if (Schema::hasTable('stock_movements')) {
            Schema::table('stock_movements', function (Blueprint $table) use ($hasIndex) {
                if ($hasIndex('stock_movements', 'idx_movements_part_date')) $table->dropIndex('idx_movements_part_date');
            });
        }

        Schema::table('assets', function (Blueprint $table) use ($hasIndex) {
            if ($hasIndex('assets', 'idx_assets_type')) $table->dropIndex('idx_assets_type');
            if ($hasIndex('assets', 'idx_assets_condition')) $table->dropIndex('idx_assets_condition');
            if ($hasIndex('assets', 'idx_assets_department')) $table->dropIndex('idx_assets_department');
        });

        if (Schema::hasTable('settings')) {
            Schema::table('settings', function (Blueprint $table) use ($hasIndex) {
                if ($hasIndex('settings', 'idx_settings_group')) $table->dropIndex('idx_settings_group');
            });
        }
    }
};
