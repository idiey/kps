<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles and permissions first
        $this->call(RolePermissionSeeder::class);

        // Site Seeder - Creates default VOCM company and sites
        $this->call(SiteSeeder::class);

        // Production Prep Seeder (Mock Data for Demo)
        $this->call(ProductionSeeder::class);

        // NOTE: The following seeders are REMOVED as part of architecture simplification:
        // - TemplateFieldTypeSeeder (template_field_types table dropped)
        // - KewPa10WorkflowSeeder (workflows/workflow_statuses tables dropped)
        // - InternalInspectionWorkflowSeeder (workflows/workflow_statuses tables dropped)
        // - BackfillWorkflowAllowedRolesSeeder (workflows table dropped)
        // - BackfillWorkflowTransitionAllowedRolesSeeder (workflow_transitions table dropped)
        // - StandardJobTemplateSeeder (job_templates table dropped)

        // Seed KEW.PA-10 test data (uses static columns now)
        // $this->call(KewPA10TestDataSeeder::class); // Replaced by ProductionSeeder

        // Seed workshop data (users, customers, jobs)
        // $this->call(WorkshopSeeder::class); // Replaced by ProductionSeeder
    }
}
