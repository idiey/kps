<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Adds 'site_admin' role to the workshop_user pivot table.
     * - site_admin: Full control over site (all features)
     * - supervisor: Job-related actions only
     * - technician: Assigned work only
     * - staff: Basic access
     */
    public function up(): void
    {
        // For MySQL, we need to modify the enum
        // First check if site_admin already exists
        if (config('database.default') === 'mysql') {
            DB::statement("ALTER TABLE workshop_user MODIFY COLUMN role ENUM('site_admin', 'supervisor', 'technician', 'staff') DEFAULT 'staff'");
        } elseif (config('database.default') === 'sqlite') {
            // SQLite doesn't support ALTER COLUMN, so we need to recreate
            // For SQLite, the enum is just stored as text, so no migration needed
            // The validation will happen at the application level
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (config('database.default') === 'mysql') {
            // First update any site_admin to supervisor before removing the enum value
            DB::table('workshop_user')->where('role', 'site_admin')->update(['role' => 'supervisor']);
            DB::statement("ALTER TABLE workshop_user MODIFY COLUMN role ENUM('supervisor', 'technician', 'staff') DEFAULT 'staff'");
        }
    }
};
