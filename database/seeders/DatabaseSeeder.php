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

        // Seed template field types
        $this->call(TemplateFieldTypeSeeder::class);

        // Seed workflows (Option 1 & Option 2)
        $this->call(KewPa10WorkflowSeeder::class);
        $this->call(InternalInspectionWorkflowSeeder::class);

        // Seed job templates (must run after workflows)
        $this->call(StandardJobTemplateSeeder::class);

        // Seed KEW.PA-10 test data
        $this->call(KewPA10TestDataSeeder::class);

        // Seed workshop data (users, customers, jobs)
        $this->call(WorkshopSeeder::class);
    }
}
