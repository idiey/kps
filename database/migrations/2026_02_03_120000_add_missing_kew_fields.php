<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds missing KEW.PA-10 fields that were implemented in the frontend
     * but missing from the initial static schema migration.
     */
    public function up(): void
    {
        Schema::table('workshop_jobs', function (Blueprint $table) {
            $table->string('kew_vehicle_registration')->nullable()->after('kew_pa_10_signatures_verified');
            $table->string('kew_asset_tag')->nullable()->after('kew_vehicle_registration');
            $table->string('kew_department_name')->nullable()->after('kew_asset_tag');
            $table->date('kew_inspection_date')->nullable()->after('kew_department_name');
            $table->string('kew_inspector_name')->nullable()->after('kew_inspection_date');
            $table->string('kew_inspector_ic')->nullable()->after('kew_inspector_name');
            $table->text('kew_findings')->nullable()->after('kew_inspector_ic');
            $table->text('kew_recommendations')->nullable()->after('kew_findings');

            // Add indexes for search performance on these text fields
            $table->index('kew_vehicle_registration');
            $table->index('kew_asset_tag');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workshop_jobs', function (Blueprint $table) {
            $table->dropIndex(['kew_vehicle_registration']);
            $table->dropIndex(['kew_asset_tag']);
            
            $table->dropColumn([
                'kew_vehicle_registration',
                'kew_asset_tag',
                'kew_department_name',
                'kew_inspection_date',
                'kew_inspector_name',
                'kew_inspector_ic',
                'kew_findings',
                'kew_recommendations',
            ]);
        });
    }
};
