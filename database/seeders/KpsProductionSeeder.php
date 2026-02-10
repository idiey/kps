<?php

namespace Database\Seeders;

use App\Models\Kps\Debt;
use App\Models\Kps\DeductionAllocation;
use App\Models\Kps\MonthlyDeduction;
use App\Models\Kps\Peneroka;
use App\Models\Kps\Site;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class KpsProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting KPS Production Data Seeding...');

        // 1. Create Users
        $this->createUsers();

        // 2. Get sites
        $sites = Site::all();

        if ($sites->isEmpty()) {
            $this->command->error('No sites found. Please run KpsSiteSeeder first.');
            return;
        }

        // 3. Create Penerokas, Debts, and Deductions for each site
        foreach ($sites as $site) {
            $this->command->info("Processing site: {$site->name}");
            $this->createPenerokasForSite($site);
        }

        $this->command->info('KPS Production Data Seeding completed!');
    }

    protected function createUsers(): void
    {
        // Global Admin (pentadbiran role)
        $globalAdmin = User::firstOrCreate(
            ['email' => 'global-admin@felda.gov.my'],
            [
                'name' => 'Global Admin',
                'password' => Hash::make('password'),
            ]
        );

        if (!$globalAdmin->hasRole('pentadbiran')) {
            $globalAdmin->assignRole('pentadbiran');
        }
        $this->command->info('  - Created Global Admin');

        // HQ Admin (company_admin role)
        $hqAdmin = User::firstOrCreate(
            ['email' => 'hq-admin@felda.gov.my'],
            [
                'name' => 'Ahmad HQ Admin',
                'password' => Hash::make('password'),
            ]
        );
        
        if (!$hqAdmin->hasRole('company_admin')) {
            $hqAdmin->assignRole('company_admin');
        }
        $this->command->info('  ✓ Created HQ Admin');

        // Site Admin 1 (penyelia role + site_admin pivot)
        $siteAdmin1 = User::firstOrCreate(
            ['email' => 'admin-st@felda.gov.my'],
            [
                'name' => 'Fatimah Site Admin',
                'password' => Hash::make('password'),
            ]
        );
        if (!$siteAdmin1->hasRole('penyelia')) {
            $siteAdmin1->assignRole('penyelia');
        }

        // Assign to FELDA Sungai Tekam
        $site1 = Site::where('code', 'FELDA-ST')->first();
        if ($site1) {
            $site1->assignUser($siteAdmin1->id, 'site_admin');
            $this->command->info('  ✓ Created Site Admin 1 (FELDA Sungai Tekam)');
        }

        // Site Admin 2 (penyelia role + site_admin pivot)
        $siteAdmin2 = User::firstOrCreate(
            ['email' => 'admin-jk@felda.gov.my'],
            [
                'name' => 'Razak Site Admin',
                'password' => Hash::make('password'),
            ]
        );
        if (!$siteAdmin2->hasRole('penyelia')) {
            $siteAdmin2->assignRole('penyelia');
        }

        // Assign to FELDA Jengka
        $site2 = Site::where('code', 'FELDA-JK')->first();
        if ($site2) {
            $site2->assignUser($siteAdmin2->id, 'site_admin');
            $this->command->info('  ✓ Created Site Admin 2 (FELDA Jengka)');
        }

        // Site Staff (kaunter role)
        $siteStaff = User::firstOrCreate(
            ['email' => 'staff-st@felda.gov.my'],
            [
                'name' => 'Siti Staff',
                'password' => Hash::make('password'),
            ]
        );
        if (!$siteStaff->hasRole('kaunter')) {
            $siteStaff->assignRole('kaunter');
        }

        // Assign to FELDA Sungai Tekam
        if ($site1) {
            $site1->assignUser($siteStaff->id, 'staff');
            $this->command->info('  ✓ Created Site Staff (FELDA Sungai Tekam)');
        }
    }

    protected function createPenerokasForSite(Site $site): void
    {
        $penerokaNames = [
            ['name' => 'Ahmad bin Abdullah', 'ic' => '650101-01-5678'],
            ['name' => 'Fatimah binti Hassan', 'ic' => '700215-02-1234'],
            ['name' => 'Razak bin Ibrahim', 'ic' => '680520-03-9876'],
            ['name' => 'Siti binti Mahmud', 'ic' => '720810-04-5432'],
            ['name' => 'Kamal bin Yusof', 'ic' => '640305-05-8765'],
            ['name' => 'Noraini binti Ahmad', 'ic' => '690925-06-2345'],
            ['name' => 'Hassan bin Mohamed', 'ic' => '710412-07-6789'],
            ['name' => 'Zainab binti Rahman', 'ic' => '660718-08-3456'],
            ['name' => 'Ibrahim bin Ali', 'ic' => '730620-09-7890'],
            ['name' => 'Aminah binti Ismail', 'ic' => '671130-10-4567'],
            ['name' => 'Yusof bin Hamid', 'ic' => '690205-11-8901'],
            ['name' => 'Halimah binti Omar', 'ic' => '740515-12-2345'],
            ['name' => 'Mohamed bin Salleh', 'ic' => '680820-13-6789'],
            ['name' => 'Rohani binti Daud', 'ic' => '720110-14-1234'],
            ['name' => 'Ali bin Hashim', 'ic' => '650925-15-5678'],
        ];

        foreach ($penerokaNames as $index => $penerokaData) {
            $peneroka = Peneroka::create([
                'site_id' => $site->id,
                'name' => $penerokaData['name'],
                'ic_number' => $penerokaData['ic'],
                'phone' => '01' . rand(1, 9) . '-' . rand(100, 999) . ' ' . rand(1000, 9999),
                'address' => "Lot {$index}, {$site->name}",
            ]);

            // Create 1-3 debts per peneroka
            $this->createDebtsForPeneroka($peneroka);

            // Create monthly deductions for last 3 months
            $this->createMonthlyDeductionsForPeneroka($peneroka, $site);
        }

        $this->command->info("  ✓ Created 15 penerokas with debts and deductions");
    }

    protected function createDebtsForPeneroka(Peneroka $peneroka): void
    {
        $debtCount = rand(1, 3);
        $debtTypes = [
            'Pinjaman Koperasi 2023',
            'Pendahuluan Tunai',
            'Pinjaman Kecemasan',
            'Pinjaman Peralatan',
            'Hutang Baja',
            'Hutang Racun Perosak',
        ];

        for ($i = 0; $i < $debtCount; $i++) {
            $originalAmount = rand(500, 5000);
            $balance = rand(100, $originalAmount);
            $priority = rand(1, 5);

            // 50% chance of having a due date
            $dueDate = rand(0, 1) ? now()->addMonths(rand(1, 12))->format('Y-m-d') : null;

            Debt::create([
                'peneroka_id' => $peneroka->id,
                'priority' => $priority,
                'balance' => $balance,
                'original_amount' => $originalAmount,
                'due_date' => $dueDate,
                'description' => $debtTypes[array_rand($debtTypes)],
            ]);
        }
    }

    protected function createMonthlyDeductionsForPeneroka(Peneroka $peneroka, Site $site): void
    {
        // Create deductions for last 3 months
        for ($i = 2; $i >= 0; $i--) {
            $month = now()->subMonths($i)->startOfMonth();
            $deductionAmount = rand(100, 500);

            // Month 2 (oldest) is closed, others are open
            $isClosed = $i === 2;
            $closedAt = $isClosed ? $month->copy()->addDays(5) : null;

            $monthlyDeduction = MonthlyDeduction::create([
                'peneroka_id' => $peneroka->id,
                'site_id' => $site->id,
                'month' => $month->format('Y-m-d'),
                'amount' => $deductionAmount,
                'unallocated_amount' => 0, // Will be calculated after allocations
                'is_closed' => $isClosed,
                'closed_at' => $closedAt,
            ]);

            // Create allocations following waterfall algorithm
            $this->createAllocationsForDeduction($monthlyDeduction, $peneroka);
        }
    }

    protected function createAllocationsForDeduction(MonthlyDeduction $monthlyDeduction, Peneroka $peneroka): void
    {
        // Get debts ordered by waterfall algorithm: priority ASC, due_date ASC (null last), created_at ASC
        $debts = $peneroka->debts()
            ->where('balance', '>', 0)
            ->orderBy('priority', 'asc')
            ->orderByRaw('due_date IS NULL, due_date ASC')
            ->orderBy('created_at', 'asc')
            ->get();

        $remainingAmount = $monthlyDeduction->amount;

        foreach ($debts as $debt) {
            if ($remainingAmount <= 0) {
                break;
            }

            // Allocate up to the debt balance or remaining amount
            $allocationAmount = min($debt->balance, $remainingAmount);

            DeductionAllocation::create([
                'monthly_deduction_id' => $monthlyDeduction->id,
                'debt_id' => $debt->id,
                'amount' => $allocationAmount,
            ]);

            // Update debt balance
            $debt->balance -= $allocationAmount;
            $debt->save();

            $remainingAmount -= $allocationAmount;
        }

        // Update unallocated amount
        $monthlyDeduction->unallocated_amount = $remainingAmount;
        $monthlyDeduction->save();
    }
}
