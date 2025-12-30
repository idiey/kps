<?php

namespace Database\Seeders;

use App\Enums\JobPriority;
use App\Models\Asset;
use App\Models\GovernmentDepartment;
use App\Models\KewPA10;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class KewPA10TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating test users for KEW.PA-10 workflow...');

        // Create test users for each role
        $admin = User::firstOrCreate(
            ['email' => 'admin@workshop.gov.my'],
            [
                'name' => 'Ahmad Ibrahim',
                'password' => Hash::make('password'),
                'role' => 'pentadbiran',
                'email_verified_at' => now(),
            ]
        );
        $this->command->info("✓ Admin Officer created: {$admin->email} / password");

        $inspector = User::firstOrCreate(
            ['email' => 'inspector@workshop.gov.my'],
            [
                'name' => 'Siti Nurhaliza',
                'password' => Hash::make('password'),
                'role' => 'pemeriksa',
                'email_verified_at' => now(),
            ]
        );
        $this->command->info("✓ Inspector created: {$inspector->email} / password");

        $supervisor = User::firstOrCreate(
            ['email' => 'supervisor@workshop.gov.my'],
            [
                'name' => 'Razak Abdullah',
                'password' => Hash::make('password'),
                'role' => 'penyelia',
                'email_verified_at' => now(),
            ]
        );
        $this->command->info("✓ Supervisor created: {$supervisor->email} / password");

        $technician = User::firstOrCreate(
            ['email' => 'technician@workshop.gov.my'],
            [
                'name' => 'Muthu Kumar',
                'password' => Hash::make('password'),
                'role' => 'juruteknik',
                'email_verified_at' => now(),
            ]
        );
        $this->command->info("✓ Technician created: {$technician->email} / password");

        $this->command->newLine();
        $this->command->info('Creating government department...');

        // Create test government department
        $dept = GovernmentDepartment::firstOrCreate(
            ['department_code' => 'MOH'],
            [
                'name' => 'Ministry of Health',
                'ministry' => 'Health',
                'contact_person' => 'Dr. Faizal Hassan',
                'phone' => '+60123456789',
                'email' => 'workshop@moh.gov.my',
                'address' => 'Level 1, Block E1, Parcel E, Putrajaya',
                'city' => 'Putrajaya',
                'state' => 'Wilayah Persekutuan',
                'postcode' => '62590',
                'is_active' => true,
            ]
        );
        $this->command->info("✓ Department created: {$dept->name} ({$dept->department_code})");

        $this->command->newLine();
        $this->command->info('Creating test asset...');

        // Create test asset
        $asset = Asset::firstOrCreate(
            ['asset_tag' => 'MOH-VEH-2025-001'],
            [
                'government_department_id' => $dept->id,
                'asset_name' => 'Toyota Hilux 4x4',
                'asset_type' => 'Vehicle',
                'description' => 'Government-issued 4x4 vehicle for emergency medical transport',
                'location' => 'MOH Main Workshop',
                'current_condition' => 'Engine overheating, radiator leaking',
                'last_maintenance_date' => now()->subMonths(3),
            ]
        );
        $this->command->info("✓ Asset created: {$asset->asset_name} ({$asset->asset_tag})");

        $this->command->newLine();
        $this->command->info('Creating KEW.PA-10 form (ready for testing)...');

        // Create KEW.PA-10 form (NOT verified yet, so you can test the verification process)
        $kew = KewPA10::firstOrCreate(
            ['kew_pa_10_number' => 'KEW.PA-10/MOH/2025/001'],
            [
                'government_department_id' => $dept->id,
                'asset_id' => $asset->id,
                'description' => 'Vehicle engine overheating during operation. Radiator showing visible leaks. Requires immediate repair as vehicle is used for emergency medical transport.',
                'priority' => JobPriority::HIGH,
                'budget_allocation_reference' => 'BA-MOH-2025-12345',
                'received_date' => now(),
                'form_completeness_verified' => false, // Test verification workflow
                'signatures_verified' => false,
                'verification_notes' => null,
            ]
        );
        $this->command->info("✓ KEW.PA-10 created: {$kew->kew_pa_10_number}");
        $this->command->warn('  → NOT verified yet (test the verification workflow)');

        $this->command->newLine();
        $this->command->line('═══════════════════════════════════════════════════════════════');
        $this->command->info('✓ Test data seeded successfully!');
        $this->command->line('═══════════════════════════════════════════════════════════════');
        $this->command->newLine();

        $this->command->info('Test Users Created:');
        $this->command->table(
            ['Role', 'Email', 'Password', 'Name'],
            [
                ['Admin Officer (Pentadbiran)', 'admin@workshop.gov.my', 'password', 'Ahmad Ibrahim'],
                ['Inspector (Pemeriksa)', 'inspector@workshop.gov.my', 'password', 'Siti Nurhaliza'],
                ['Supervisor (Penyelia)', 'supervisor@workshop.gov.my', 'password', 'Razak Abdullah'],
                ['Technician (Juruteknik)', 'technician@workshop.gov.my', 'password', 'Muthu Kumar'],
            ]
        );

        $this->command->newLine();
        $this->command->info('Next Steps:');
        $this->command->line('1. Login as admin@workshop.gov.my / password');
        $this->command->line('2. Navigate to /kew-pa-10');
        $this->command->line("3. Open KEW.PA-10/MOH/2025/001");
        $this->command->line('4. Click "Verify Form" and complete verification');
        $this->command->line('5. Click "Create Workshop Job"');
        $this->command->line('6. Follow the testing guide in docs/07-testing/01-kew-pa-10-workflow-testing.md');
        $this->command->newLine();
    }
}
