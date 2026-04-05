<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolePermissionSeeder::class);
        $this->call(KpsSiteSeeder::class);
        $this->call(KpsProductionSeeder::class);
        $this->call(KpsDemoFlowSeeder::class);
    }
}
