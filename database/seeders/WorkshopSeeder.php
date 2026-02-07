<?php

namespace Database\Seeders;

use App\Enums\JobPriority;
use App\Enums\JobStatus;
use App\Models\Customer;
use App\Models\User;
use App\Models\WorkshopJob;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class WorkshopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create users with different roles
        $admin = User::firstOrCreate(
            ['email' => 'admin@workshop.gov.my'],
            [
                'name' => 'Ahmad bin Abdullah',
                'password' => Hash::make('password'),
                'phone' => '03-88881234',
                'department' => 'Pentadbiran',
            ]
        );
        $admin->assignRole('pentadbiran');

        $supervisor = User::firstOrCreate(
            ['email' => 'supervisor@workshop.gov.my'],
            [
                'name' => 'Siti Nurhaliza',
                'password' => Hash::make('password'),
                'phone' => '03-88881235',
                'department' => 'Teknikal',
            ]
        );
        $supervisor->assignRole('penyelia');

        $technician1 = User::firstOrCreate(
            ['email' => 'tech1@workshop.gov.my'],
            [
                'name' => 'Mohamed bin Hassan',
                'password' => Hash::make('password'),
                'phone' => '03-88881236',
                'department' => 'Teknikal',
            ]
        );
        $technician1->assignRole('juruteknik');

        $technician2 = User::firstOrCreate(
            ['email' => 'tech2@workshop.gov.my'],
            [
                'name' => 'Tan Wei Ming',
                'password' => Hash::make('password'),
                'phone' => '03-88881237',
                'department' => 'Teknikal',
            ]
        );
        $technician2->assignRole('juruteknik');

        $inspector = User::firstOrCreate(
            ['email' => 'inspector@workshop.gov.my'],
            [
                'name' => 'Rajeswari Devi',
                'password' => Hash::make('password'),
                'phone' => '03-88881238',
                'department' => 'Pemeriksaan',
            ]
        );
        $inspector->assignRole('pemeriksa');

        $approver = User::firstOrCreate(
            ['email' => 'approver@workshop.gov.my'],
            [
                'name' => 'Dato\' Ibrahim bin Mahmud',
                'password' => Hash::make('password'),
                'phone' => '03-88881239',
                'department' => 'Pengurusan',
            ]
        );
        $approver->assignRole('pelulus');

        // Create customers
        $customers = [
            [
                'name' => 'Jabatan Kerja Raya',
                'email' => 'jkr@gov.my',
                'phone' => '03-26172222',
                'company_name' => 'Jabatan Kerja Raya Malaysia',
                'address' => 'Jalan Sultan Salahuddin',
                'city' => 'Kuala Lumpur',
                'state' => 'Wilayah Persekutuan',
                'postcode' => '50480',
                'customer_type' => 'government',
                'department' => 'Jabatan Kerja Raya',
            ],
            [
                'name' => 'Polis Diraja Malaysia',
                'email' => 'pdrm@rmp.gov.my',
                'phone' => '03-22662222',
                'company_name' => 'Polis Diraja Malaysia',
                'address' => 'Bukit Aman',
                'city' => 'Kuala Lumpur',
                'state' => 'Wilayah Persekutuan',
                'postcode' => '50560',
                'customer_type' => 'government',
                'department' => 'PDRM',
            ],
            [
                'name' => 'Ali bin Abu',
                'email' => 'ali.abu@email.com',
                'phone' => '012-3456789',
                'ic_number' => '850101-01-5678',
                'address' => 'No. 123, Jalan Merdeka',
                'city' => 'Shah Alam',
                'state' => 'Selangor',
                'postcode' => '40000',
                'customer_type' => 'individual',
            ],
        ];

        foreach ($customers as $customerData) {
            $customer = Customer::firstOrCreate(
                ['email' => $customerData['email'] ?? null, 'name' => $customerData['name']],
                $customerData
            );

            // Create some jobs for each customer
            $jobsCount = rand(1, 3);
            for ($i = 0; $i < $jobsCount; $i++) {
                $status = [JobStatus::NEW, JobStatus::IN_PROGRESS, JobStatus::COMPLETED][array_rand([0, 1, 2])];
                $assignedTo = rand(0, 1) ? [$technician1->id, $technician2->id][array_rand([0, 1])] : null;

                $job = WorkshopJob::create([
                    'customer_id' => $customer->id,
                    'assigned_to' => $assignedTo,
                    'title' => $this->getRandomJobTitle(),
                    'description' => $this->getRandomJobDescription(),
                    'status' => $status,
                    'priority' => [JobPriority::LOW, JobPriority::MEDIUM, JobPriority::HIGH, JobPriority::URGENT][array_rand([0, 1, 2, 3])],
                    'vehicle_registration' => $customer->customer_type === 'government' ? $this->getRandomVehicleReg() : null,
                    'estimated_cost' => rand(500, 5000),
                    'due_date' => now()->addDays(rand(1, 30)),
                ]);

                // Add initial status history
                $job->statusHistories()->create([
                    'user_id' => $admin->id,
                    'from_status' => null,
                    'to_status' => JobStatus::NEW,
                    'changed_at' => $job->created_at,
                ]);

                // Add status progression if not new
                if ($status !== JobStatus::NEW) {
                    $job->statusHistories()->create([
                        'user_id' => $supervisor->id,
                        'from_status' => JobStatus::NEW,
                        'to_status' => JobStatus::IN_PROGRESS,
                        'changed_at' => $job->created_at->addHours(2),
                    ]);
                }

                if ($status === JobStatus::COMPLETED) {
                    $job->statusHistories()->create([
                        'user_id' => $assignedTo ?? $technician1->id,
                        'from_status' => JobStatus::IN_PROGRESS,
                        'to_status' => JobStatus::COMPLETED,
                        'changed_at' => $job->created_at->addDays(rand(1, 5)),
                    ]);
                }

                // Add assignment if assigned
                if ($assignedTo) {
                    $job->assignments()->create([
                        'assigned_by' => $supervisor->id,
                        'assigned_to' => $assignedTo,
                        'notes' => 'Initial assignment',
                        'assigned_at' => $job->created_at->addHours(1),
                        'is_current' => true,
                    ]);
                }

                // Add some notes
                $notesCount = rand(1, 3);
                for ($j = 0; $j < $notesCount; $j++) {
                    $job->notes()->create([
                        'user_id' => $assignedTo ?? $admin->id,
                        'content' => $this->getRandomNote(),
                        'is_public' => rand(0, 1) === 1,
                        'note_type' => ['general', 'diagnostic', 'repair'][array_rand([0, 1, 2])],
                    ]);
                }
            }
        }

        $this->command->info('Workshop data seeded successfully!');
        $this->command->info('');
        $this->command->info('Test Users:');
        $this->command->info('Admin: admin@workshop.gov.my / password');
        $this->command->info('Supervisor: supervisor@workshop.gov.my / password');
        $this->command->info('Technician 1: tech1@workshop.gov.my / password');
        $this->command->info('Technician 2: tech2@workshop.gov.my / password');
        $this->command->info('Inspector: inspector@workshop.gov.my / password');
        $this->command->info('Approver: approver@workshop.gov.my / password');
    }

    private function getRandomJobTitle(): string
    {
        $titles = [
            'Engine Oil Change and Filter Replacement',
            'Brake Pad Replacement',
            'Air Conditioning System Repair',
            'Tire Rotation and Alignment',
            'Battery Replacement',
            'Transmission Fluid Service',
            'Suspension System Inspection',
            'Electrical System Diagnosis',
            'Cooling System Flush',
            'Exhaust System Repair',
        ];

        return $titles[array_rand($titles)];
    }

    private function getRandomJobDescription(): string
    {
        $descriptions = [
            'Regular maintenance service required',
            'Customer reported unusual noise during operation',
            'Preventive maintenance as per schedule',
            'Component showing signs of wear and tear',
            'Routine inspection revealed issue',
            'Follow-up service from previous repair',
            'Customer requested immediate attention',
            'Scheduled replacement due to mileage',
        ];

        return $descriptions[array_rand($descriptions)];
    }

    private function getRandomNote(): string
    {
        $notes = [
            'Inspection completed. Parts ordered.',
            'Work in progress. Expected completion tomorrow.',
            'Customer notified of additional work required.',
            'Awaiting parts delivery from supplier.',
            'Test drive completed successfully.',
            'All systems checked and verified.',
            'Recommended follow-up inspection in 6 months.',
            'Work completed ahead of schedule.',
        ];

        return $notes[array_rand($notes)];
    }

    private function getRandomVehicleReg(): string
    {
        $prefixes = ['WA', 'WB', 'WC', 'WD', 'WE'];
        $numbers = rand(1000, 9999);
        $letters = chr(rand(65, 90));

        return $prefixes[array_rand($prefixes)] . $numbers . $letters;
    }
}
