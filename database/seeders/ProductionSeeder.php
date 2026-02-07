<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;
use App\Models\Workshop;
use App\Models\WorkshopJob;
use App\Models\JobStatusHistory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Enums\JobStatus;

class ProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting Production Data Seeding...');

        // 1. Ensure Roles/Users exist
        $admin = User::firstOrCreate(
            ['email' => 'admin@workshop.gov.my'],
            [
                'name' => 'Ahmad Admin',
                'password' => Hash::make('password'),
            ]
        );
        if (!$admin->hasRole('pentadbiran')) {
             $admin->assignRole('pentadbiran');
        }

        $supervisor = User::firstOrCreate(
            ['email' => 'supervisor@workshop.gov.my'],
            [
                'name' => 'Razak Supervisor',
                'password' => Hash::make('password'),
            ]
        );
        
        // Note: RolePermissionSeeder should have run before this to create Spatie roles
        // We ensure the user has the Spatie role as well
        if (!$supervisor->hasRole('penyelia')) {
             $supervisor->assignRole('penyelia');
        }

        $inspector = User::firstOrCreate(
            ['email' => 'inspector@workshop.gov.my'],
            [
                'name' => 'Siti Inspector',
                'password' => Hash::make('password'),
            ]
        );
        
        if (!$inspector->hasRole('pemeriksa')) {
             $inspector->assignRole('pemeriksa');
        }

        $technician = User::firstOrCreate(
            ['email' => 'technician@workshop.gov.my'],
            [
                'name' => 'Ali Technician',
                'password' => Hash::make('password'),
            ]
        );
        
        if (!$technician->hasRole('juruteknik')) {
             $technician->assignRole('juruteknik');
        }

        $frontdesk = User::firstOrCreate(
            ['email' => 'frontdesk@workshop.gov.my'],
            [
                'name' => 'Zarina Frontdesk',
                'password' => Hash::make('password'),
            ]
        );
        
        if (!$frontdesk->hasRole('kaunter')) {
             $frontdesk->assignRole('kaunter');
        }

        $this->command->info('Users verified/created.');

        // 2. Get or create default VOCM company and sites
        $vocm = SiteSeeder::getDefaultCompany();
        $this->command->info('Default VOCM company retrieved/created.');

        // 3. Create Workshops (Sites) if not already created by SiteSeeder
        $workshop1 = Workshop::firstOrCreate(
            ['code' => 'VOCM-PJ'],
            [
                'company_id' => $vocm->id,
                'name' => 'Bengkel VOCM Petaling Jaya',
                'address' => 'Jalan Lapangan Terbang, Subang',
                'phone' => '03-7846 1234',
                'is_active' => true,
            ]
        );

        $workshop2 = Workshop::firstOrCreate(
            ['code' => 'VOCM-KL'],
            [
                'company_id' => $vocm->id,
                'name' => 'Bengkel VOCM Kuala Lumpur',
                'address' => 'Jalan Duta, Kuala Lumpur',
                'phone' => '03-6201 5678',
                'is_active' => true,
            ]
        );

        $workshop3 = Workshop::firstOrCreate(
            ['code' => 'VOCM-SHAH'],
            [
                'company_id' => $vocm->id,
                'name' => 'Bengkel VOCM Shah Alam',
                'address' => 'Persiaran Kayangan, Shah Alam',
                'phone' => '03-5544 3322',
                'is_active' => true,
            ]
        );

        $this->command->info('Workshops (Sites) created.');

        // 4. Assign Users to Workshops
        // Workshop 1 assignments
        $workshop1->assignUser($supervisor->id, 'supervisor');
        $workshop1->assignUser($inspector->id, 'technician');
        $workshop1->assignUser($technician->id, 'technician');
        $workshop1->assignUser($frontdesk->id, 'staff');
        
        // Workshop 2 assignments
        $workshop2->assignUser($supervisor->id, 'supervisor');
        $workshop2->assignUser($technician->id, 'technician');
        
        $this->command->info('Users assigned to workshops.');

        // 5. Link admin to VOCM for demo purposes
        if (!$admin->company_id) {
            // Keep admin as global (null company_id) for full access
            // Create a VOCM-level admin for scoped demo
            $vocmAdmin = User::firstOrCreate(
                ['email' => 'vocm-admin@workshop.gov.my'],
                [
                    'name' => 'Badrul VOCM Admin',
                    'password' => Hash::make('password'),
                    'company_id' => $vocm->id,
                ]
            );
            if (!$vocmAdmin->hasRole('pentadbiran')) {
                $vocmAdmin->assignRole('pentadbiran');
            }
        }

        $this->command->info('VOCM admin created for scoped demo.');

        // 6. Create a dedicated Site Admin for one of the workshops
        // Site admins use PENYELIA role (supervisor-level permissions) but get elevated
        // privileges through the 'site_admin' pivot role on the workshop_user table.
        // This means they can manage their assigned site but NOT access global admin features.
        $siteAdmin = User::firstOrCreate(
            ['email' => 'siteadmin@workshop.gov.my'],
            [
                'name' => 'Sara Site Admin',
                'password' => Hash::make('password'),
                // Site admins have supervisor-level base permissions
            ]
        );
        if (!$siteAdmin->hasRole('penyelia')) {
             $siteAdmin->assignRole('penyelia');
        }
        
        // Assign Site Admin to Workshop 1 with site_admin pivot role
        // The site_admin pivot role grants additional site management capabilities
        $workshop1->assignUser($siteAdmin->id, 'site_admin');
        
        $this->command->info('Site Admin created and assigned (with penyelia role + site_admin pivot).');

        // 2. Clear existing demo jobs (optional, but good for fresh state)
        // WorkshopJob::truncate(); // Dangerous in production, okay for Production Mockup Seeder

        // 3. Seed Normal Jobs
        WorkshopJob::factory()->count(5)->normal()->create([
            'status' => JobStatus::NEW,
            'assigned_to' => null,
        ]);
        WorkshopJob::factory()->count(5)->normal()->create([
            'status' => JobStatus::IN_PROGRESS,
            'assigned_to' => $inspector->id,
        ]);
        $this->command->info('Normal Jobs created.');

        // 4. Seed KEW.PA-10 Jobs
        
        // Pending Approval
        WorkshopJob::factory()->count(5)->kew()->pendingApproval()->create([
            'kew_inspector_name' => $inspector->name,
            'kew_inspector_ic' => '900101-01-1234',
        ]);

        // Approved (Mock history)
        $approvedJobs = WorkshopJob::factory()->count(3)->kew()->approved()->create([
            'kew_inspector_name' => $inspector->name,
            'kew_approved_by_id' => $supervisor->id,
        ]);
        
        foreach ($approvedJobs as $job) {
            JobStatusHistory::create([
                'workshop_job_id' => $job->id,
                'from_status' => JobStatus::INSPECTION_IN_PROGRESS,
                'to_status' => JobStatus::INSPECTION_APPROVED,
                'changed_at' => now(),
                'user_id' => $supervisor->id,
                'notes' => 'Approved via Batch Seed',
            ]);
        }

        // Rejected
        WorkshopJob::factory()->count(2)->kew()->rejected()->create([
            'kew_inspector_name' => $inspector->name,
            'kew_approved_by_id' => $supervisor->id,
            'kew_rejection_reason' => 'Incomplete findings description.',
        ]);

        $this->command->info('KEW.PA-10 Mock Jobs created (Pending, Approved, Rejected).');
    }
}
