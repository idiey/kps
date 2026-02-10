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

        // KPS Site Seeder - Creates FELDA sites
        $this->call(KpsSiteSeeder::class);

        // KPS Production Seeder - Creates users, penerokas, debts, and deductions
        $this->call(KpsProductionSeeder::class);

        // NOTE: The following seeders are DEPRECATED as part of KPS implementation:
        // - SiteSeeder (replaced by KpsSiteSeeder)
        // - ProductionSeeder (replaced by KpsProductionSeeder)
        // - WorkshopSeeder (workshop-specific, not needed for KPS)
        // - KewPA10TestDataSeeder (workshop-specific, not needed for KPS)
        // - All workflow-related seeders (workflows table dropped)
        // - All template-related seeders (template tables dropped)
    }
}
