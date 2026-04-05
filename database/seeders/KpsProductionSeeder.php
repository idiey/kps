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
use Spatie\Permission\Models\Role;

class KpsProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting KPS Production Data Seeding...');

        $this->createUsers();
        $this->normalizeAssignedUserRoles();
        $this->removeUnusedRoles();

        $sites = Site::all();

        if ($sites->isEmpty()) {
            $this->command->error('No sites found. Please run KpsSiteSeeder first.');

            return;
        }

        foreach ($sites as $site) {
            $this->command->info("Processing site: {$site->name}");
            $this->createPenerokasForSite($site);
        }

        $this->command->info('KPS Production Data Seeding completed!');
    }

    protected function createUsers(): void
    {
        $globalAdmin = User::firstOrCreate(
            ['email' => 'global-admin@felda.gov.my'],
            [
                'name' => 'Global Admin',
                'password' => Hash::make('password'),
            ]
        );
        $globalAdmin->syncRoles(['pentadbiran']);
        $this->command->info('  - Created Global Admin');

        $hqAdmin = User::firstOrCreate(
            ['email' => 'hq-admin@felda.gov.my'],
            [
                'name' => 'Ahmad HQ Admin',
                'password' => Hash::make('password'),
            ]
        );
        $hqAdmin->syncRoles(['company_admin']);
        $this->command->info('  - Created HQ Admin');

        $siteAdmin1 = User::firstOrCreate(
            ['email' => 'admin-st@felda.gov.my'],
            [
                'name' => 'Fatimah Site Admin',
                'password' => Hash::make('password'),
            ]
        );
        $siteAdmin1->syncRoles(['site_admin']);

        $site1 = Site::where('code', 'FELDA-ST')->first();
        if ($site1) {
            $site1->assignUser($siteAdmin1->id, 'site_admin');
            $this->command->info('  - Created Site Admin 1 (FELDA Sungai Tekam)');
        }

        $siteAdmin2 = User::firstOrCreate(
            ['email' => 'admin-jk@felda.gov.my'],
            [
                'name' => 'Razak Site Admin',
                'password' => Hash::make('password'),
            ]
        );
        $siteAdmin2->syncRoles(['site_admin']);

        $site2 = Site::where('code', 'FELDA-JK')->first();
        if ($site2) {
            $site2->assignUser($siteAdmin2->id, 'site_admin');
            $this->command->info('  - Created Site Admin 2 (FELDA Jengka)');
        }

        $siteStaff = User::firstOrCreate(
            ['email' => 'staff-st@felda.gov.my'],
            [
                'name' => 'Siti Staff',
                'password' => Hash::make('password'),
            ]
        );
        $siteStaff->syncRoles(['staff']);

        if ($site1) {
            $site1->assignUser($siteStaff->id, 'staff');
            $this->command->info('  - Created Site Staff (FELDA Sungai Tekam)');
        }
    }

    protected function normalizeAssignedUserRoles(): void
    {
        $users = User::with('kpsSites')->get();

        foreach ($users as $user) {
            if ($user->hasRole(['pentadbiran', 'company_admin'])) {
                continue;
            }

            if ($user->kpsSites->contains(fn (Site $site) => $site->pivot?->role === 'site_admin')) {
                $user->syncRoles(['site_admin']);

                continue;
            }

            if ($user->kpsSites->isNotEmpty()) {
                $user->syncRoles(['staff']);
            }
        }
    }

    protected function removeUnusedRoles(): void
    {
        Role::query()
            ->whereDoesntHave('users')
            ->whereDoesntHave('permissions')
            ->get()
            ->each(function (Role $role): void {
                $role->delete();
            });
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

            $this->createDebtsForPeneroka($peneroka);
            $this->createMonthlyDeductionsForPeneroka($peneroka, $site);
        }

        $this->command->info('  - Created 15 penerokas with debts and deductions');
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
        for ($i = 2; $i >= 0; $i--) {
            $month = now()->subMonths($i)->startOfMonth();
            $deductionAmount = rand(100, 500);

            $isClosed = $i === 2;
            $closedAt = $isClosed ? $month->copy()->addDays(5) : null;

            $monthlyDeduction = MonthlyDeduction::create([
                'peneroka_id' => $peneroka->id,
                'site_id' => $site->id,
                'month' => $month->format('Y-m-d'),
                'amount' => $deductionAmount,
                'unallocated_amount' => 0,
                'is_closed' => $isClosed,
                'closed_at' => $closedAt,
            ]);

            $this->createAllocationsForDeduction($monthlyDeduction, $peneroka);
        }
    }

    protected function createAllocationsForDeduction(MonthlyDeduction $monthlyDeduction, Peneroka $peneroka): void
    {
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

            $allocationAmount = min($debt->balance, $remainingAmount);

            DeductionAllocation::create([
                'monthly_deduction_id' => $monthlyDeduction->id,
                'debt_id' => $debt->id,
                'amount' => $allocationAmount,
            ]);

            $debt->balance -= $allocationAmount;
            $debt->save();

            $remainingAmount -= $allocationAmount;
        }

        $monthlyDeduction->unallocated_amount = $remainingAmount;
        $monthlyDeduction->save();
    }
}
