# KPS Demo Flow Seed Data Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Add deterministic demo seed data that gives one dedicated site-admin workflow site with obvious peneroka cases for potongan, allocations, month closing, and statements.

**Architecture:** Create a new dedicated seeder that resets and rebuilds one demo site from fixed data, then wire it into `DatabaseSeeder`. Verify it through focused Pest feature tests that exercise both seeding idempotence and the existing site routes. Use the real `AllocationService` and `MonthlyClosingService` so the seeded state matches runtime behavior.

**Tech Stack:** Laravel seeders, Pest, Eloquent models, existing KPS services

---

## File Structure

- Create: `database/seeders/KpsDemoFlowSeeder.php`
- Modify: `database/seeders/DatabaseSeeder.php`
- Create: `tests/Feature/KpsDemoFlowSeederTest.php`

### Task 1: Write the Failing Seeder Tests

**Files:**
- Create: `tests/Feature/KpsDemoFlowSeederTest.php`

- [ ] **Step 1: Write the failing test for default seeder integration**

```php
<?php

use App\Models\Kps\Site;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('database seeder includes the dedicated demo flow site', function () {
    $this->seed(\Database\Seeders\DatabaseSeeder::class);

    expect(Site::where('code', 'FELDA-DEMO')->exists())->toBeTrue();
});
```

- [ ] **Step 2: Run the single test to verify it fails**

Run:

```powershell
php artisan test tests/Feature/KpsDemoFlowSeederTest.php --filter="database seeder includes the dedicated demo flow site"
```

Expected: FAIL because `FELDA-DEMO` does not exist yet

- [ ] **Step 3: Add the failing idempotence test**

```php
test('demo flow seeder is idempotent for site, admin, and demo peneroka cases', function () {
    $this->seed([
        \Database\Seeders\RolePermissionSeeder::class,
        \Database\Seeders\KpsSiteSeeder::class,
        \Database\Seeders\KpsDemoFlowSeeder::class,
    ]);

    $this->seed(\Database\Seeders\KpsDemoFlowSeeder::class);

    $demoSite = \App\Models\Kps\Site::where('code', 'FELDA-DEMO')->firstOrFail();

    expect(\App\Models\User::where('email', 'demo-site-admin@felda.gov.my')->count())->toBe(1);
    expect(\App\Models\Kps\Peneroka::where('site_id', $demoSite->id)->count())->toBe(4);
    expect(\App\Models\Kps\MonthlyDeduction::where('site_id', $demoSite->id)->count())->toBe(6);
});
```

- [ ] **Step 4: Run the idempotence test to verify it fails**

Run:

```powershell
php artisan test tests/Feature/KpsDemoFlowSeederTest.php --filter="demo flow seeder is idempotent"
```

Expected: FAIL because the seeder class does not exist yet

- [ ] **Step 5: Add the failing route-visibility tests**

```php
test('demo flow seeder exposes open and closed payment-flow data through site routes', function () {
    $this->seed([
        \Database\Seeders\RolePermissionSeeder::class,
        \Database\Seeders\KpsSiteSeeder::class,
        \Database\Seeders\KpsDemoFlowSeeder::class,
    ]);

    $site = \App\Models\Kps\Site::where('code', 'FELDA-DEMO')->firstOrFail();
    $admin = \App\Models\User::where('email', 'demo-site-admin@felda.gov.my')->firstOrFail();

    $this->actingAs($admin)
        ->get("/kps/sites/{$site->id}/potongan?month=2026-04")
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('site.code', 'FELDA-DEMO')
            ->where('deductions.data.0.month', '2026-04-01')
        );

    $this->actingAs($admin)
        ->get("/kps/sites/{$site->id}/allocations?month=2026-03")
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('site.code', 'FELDA-DEMO')
            ->where('deductions.data.0.is_closed', true)
        );
});

test('demo flow seeder exposes statement-ready peneroka data through reports', function () {
    $this->seed([
        \Database\Seeders\RolePermissionSeeder::class,
        \Database\Seeders\KpsSiteSeeder::class,
        \Database\Seeders\KpsDemoFlowSeeder::class,
    ]);

    $site = \App\Models\Kps\Site::where('code', 'FELDA-DEMO')->firstOrFail();
    $admin = \App\Models\User::where('email', 'demo-site-admin@felda.gov.my')->firstOrFail();
    $peneroka = \App\Models\Kps\Peneroka::where('site_id', $site->id)
        ->where('name', 'Dahlia Demo Penyata')
        ->firstOrFail();

    $this->actingAs($admin)
        ->get("/kps/sites/{$site->id}/reports")
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('site.code', 'FELDA-DEMO')
            ->has('penerokas')
        );

    $this->actingAs($admin)
        ->get("/kps/sites/{$site->id}/reports/peneroka/{$peneroka->id}")
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('peneroka.name', 'Dahlia Demo Penyata')
            ->has('deductions')
            ->has('peneroka.debts')
        );
});
```

- [ ] **Step 6: Run the route-visibility tests to verify they fail**

Run:

```powershell
php artisan test tests/Feature/KpsDemoFlowSeederTest.php --filter="demo flow seeder exposes"
```

Expected: FAIL because the seeder and records do not exist yet

### Task 2: Implement the Dedicated Demo Seeder

**Files:**
- Create: `database/seeders/KpsDemoFlowSeeder.php`

- [ ] **Step 1: Create the seeder skeleton with fixed constants**

```php
<?php

namespace Database\Seeders;

use App\Models\Kps\AuditLog;
use App\Models\Kps\Debt;
use App\Models\Kps\MonthlyDeduction;
use App\Models\Kps\Peneroka;
use App\Models\Kps\Site;
use App\Models\User;
use App\Services\Kps\AllocationService;
use App\Services\Kps\MonthlyClosingService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class KpsDemoFlowSeeder extends Seeder
{
    private const SITE_CODE = 'FELDA-DEMO';
    private const SITE_NAME = 'FELDA Demo Pembayaran';
    private const ADMIN_EMAIL = 'demo-site-admin@felda.gov.my';
    private const OPEN_MONTH = '2026-04-01';
    private const CLOSED_MONTH = '2026-03-01';
    private const HISTORY_MONTH = '2026-02-01';

    public function run(): void
    {
    }
}
```

- [ ] **Step 2: Implement `run()` and site/admin creation**

```php
public function run(): void
{
    $site = Site::withTrashed()->firstOrNew(['code' => self::SITE_CODE]);
    $site->fill([
        'name' => self::SITE_NAME,
        'address' => 'Lot Demo KPS, 28000 Temerloh, Pahang',
        'phone' => '09-900 1000',
        'email' => 'demo-pembayaran@felda.gov.my',
        'is_active' => true,
        'deleted_at' => null,
    ]);
    $site->save();

    $admin = User::firstOrCreate(
        ['email' => self::ADMIN_EMAIL],
        ['name' => 'Demo Site Admin', 'password' => Hash::make('password')]
    );
    $admin->syncRoles(['site_admin']);
    $site->assignUser($admin->id, 'site_admin');

    $this->resetDemoSite($site);
    $this->seedCases($site);
}
```

- [ ] **Step 3: Implement the reset step so re-runs are deterministic**

```php
private function resetDemoSite(Site $site): void
{
    $penerokaIds = Peneroka::withTrashed()
        ->where('site_id', $site->id)
        ->pluck('id');

    $deductionIds = MonthlyDeduction::query()
        ->where('site_id', $site->id)
        ->pluck('id');

    \App\Models\Kps\DeductionAllocation::query()
        ->whereIn('monthly_deduction_id', $deductionIds)
        ->delete();

    MonthlyDeduction::query()
        ->where('site_id', $site->id)
        ->delete();

    Debt::withTrashed()
        ->whereIn('peneroka_id', $penerokaIds)
        ->forceDelete();

    Peneroka::withTrashed()
        ->where('site_id', $site->id)
        ->forceDelete();

    AuditLog::query()
        ->where('site_id', $site->id)
        ->delete();
}
```

- [ ] **Step 4: Implement fixed case seeding using real services**

```php
private function seedCases(Site $site): void
{
    $allocationService = app(AllocationService::class);
    $closingService = app(MonthlyClosingService::class);
    $admin = User::where('email', self::ADMIN_EMAIL)->firstOrFail();

    $ahmad = $this->createPeneroka($site, 'Ahmad Demo Penuh', '700101-01-1001');
    $this->createDebt($ahmad, 1, 120, 'Hutang Baja Demo Penuh', '2026-04-10');
    $this->createDebt($ahmad, 2, 80, 'Hutang Racun Demo Penuh', '2026-04-20');
    $allocationService->allocate($this->createDeduction($site, $ahmad, self::OPEN_MONTH, 150));

    $badrul = $this->createPeneroka($site, 'Badrul Demo Baki', '700202-02-2002');
    $this->createDebt($badrul, 1, 40, 'Hutang Input Demo Baki', '2026-04-09');
    $this->createDebt($badrul, 2, 30, 'Hutang Tunai Demo Baki', '2026-04-18');
    $allocationService->allocate($this->createDeduction($site, $badrul, self::OPEN_MONTH, 120));

    $cheSiti = $this->createPeneroka($site, 'Che Siti Demo Tutup', '700303-03-3003');
    $this->createDebt($cheSiti, 1, 90, 'Hutang Tutup Bulan', '2026-03-15');
    $allocationService->allocate($this->createDeduction($site, $cheSiti, self::CLOSED_MONTH, 90));

    $dahlia = $this->createPeneroka($site, 'Dahlia Demo Penyata', '700404-04-4004');
    $this->createDebt($dahlia, 1, 150, 'Hutang Penyata 1', '2026-02-18');
    $this->createDebt($dahlia, 2, 130, 'Hutang Penyata 2', '2026-03-20');
    $this->createDebt($dahlia, 3, 110, 'Hutang Penyata 3', '2026-04-22');
    $allocationService->allocate($this->createDeduction($site, $dahlia, self::HISTORY_MONTH, 60));
    $allocationService->allocate($this->createDeduction($site, $dahlia, self::CLOSED_MONTH, 80));
    $allocationService->allocate($this->createDeduction($site, $dahlia, self::OPEN_MONTH, 100));

    $closingService->closeMonth($site, \Illuminate\Support\Carbon::parse(self::HISTORY_MONTH), $admin);
    $closingService->closeMonth($site, \Illuminate\Support\Carbon::parse(self::CLOSED_MONTH), $admin);
}
```

- [ ] **Step 5: Add helper creators for peneroka, debt, and deduction**

```php
private function createPeneroka(Site $site, string $name, string $ic): Peneroka
{
    return Peneroka::create([
        'site_id' => $site->id,
        'name' => $name,
        'ic_number' => $ic,
        'phone' => '013-880 1200',
        'address' => 'Alamat demo '.$name,
    ]);
}

private function createDebt(Peneroka $peneroka, int $priority, float $amount, string $description, string $dueDate): Debt
{
    return Debt::create([
        'peneroka_id' => $peneroka->id,
        'priority' => $priority,
        'balance' => $amount,
        'original_amount' => $amount,
        'due_date' => $dueDate,
        'description' => $description,
    ]);
}

private function createDeduction(Site $site, Peneroka $peneroka, string $month, float $amount): MonthlyDeduction
{
    return MonthlyDeduction::create([
        'site_id' => $site->id,
        'peneroka_id' => $peneroka->id,
        'month' => $month,
        'amount' => $amount,
        'unallocated_amount' => 0,
        'is_closed' => false,
        'closed_at' => null,
    ]);
}
```

- [ ] **Step 6: Run the seeder tests to verify they now move closer to green**

Run:

```powershell
php artisan test tests/Feature/KpsDemoFlowSeederTest.php
```

Expected: remaining failures only from `DatabaseSeeder` integration until that file is updated

### Task 3: Wire the Seeder Into Default Seeding

**Files:**
- Modify: `database/seeders/DatabaseSeeder.php`

- [ ] **Step 1: Add the demo-flow seeder to the default seed order**

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RolePermissionSeeder::class);
        $this->call(KpsSiteSeeder::class);
        $this->call(KpsProductionSeeder::class);
        $this->call(KpsDemoFlowSeeder::class);
    }
}
```

- [ ] **Step 2: Run the integration test to verify it passes**

Run:

```powershell
php artisan test tests/Feature/KpsDemoFlowSeederTest.php --filter="database seeder includes the dedicated demo flow site"
```

Expected: PASS

### Task 4: Make the Route-Visible Assertions Precise and Green

**Files:**
- Modify: `tests/Feature/KpsDemoFlowSeederTest.php`

- [ ] **Step 1: Tighten the route assertions to the seeded names and states**

```php
$this->actingAs($admin)
    ->get("/kps/sites/{$site->id}/potongan?month=2026-04")
    ->assertOk()
    ->assertInertia(fn ($page) => $page
        ->where('site.code', 'FELDA-DEMO')
        ->where('selectedMonth', '2026-04')
        ->has('deductions.data', 3)
    );

expect(\App\Models\Kps\MonthlyDeduction::where('site_id', $site->id)->where('month', '2026-04-01')->where('is_closed', false)->count())->toBe(3);
expect(\App\Models\Kps\MonthlyDeduction::where('site_id', $site->id)->where('month', '2026-03-01')->where('is_closed', true)->count())->toBe(2);
expect(\App\Models\Kps\Peneroka::where('site_id', $site->id)->pluck('name')->all())->toBe([
    'Ahmad Demo Penuh',
    'Badrul Demo Baki',
    'Che Siti Demo Tutup',
    'Dahlia Demo Penyata',
]);
```

- [ ] **Step 2: Run the full test file**

Run:

```powershell
php artisan test tests/Feature/KpsDemoFlowSeederTest.php
```

Expected: PASS

- [ ] **Step 3: Run the existing flow tests to confirm no regression**

Run:

```powershell
php artisan test tests/Feature/KpsAllocationWorkflowTest.php tests/Feature/KpsReportsTest.php tests/Feature/KpsDemoFlowSeederTest.php
```

Expected: PASS

### Task 5: Commit the Working Slice

**Files:**
- Create: `database/seeders/KpsDemoFlowSeeder.php`
- Modify: `database/seeders/DatabaseSeeder.php`
- Create: `tests/Feature/KpsDemoFlowSeederTest.php`

- [ ] **Step 1: Review the final diff for only the intended files**

Run:

```powershell
git -c safe.directory='C:/Users/zuraidiismail/RnD/kps' diff -- database/seeders/KpsDemoFlowSeeder.php database/seeders/DatabaseSeeder.php tests/Feature/KpsDemoFlowSeederTest.php
```

Expected: diff only shows the demo seeder, default seeder wiring, and new tests

- [ ] **Step 2: Commit the slice**

Run:

```powershell
git add database/seeders/KpsDemoFlowSeeder.php database/seeders/DatabaseSeeder.php tests/Feature/KpsDemoFlowSeederTest.php
git commit -m "feat: add deterministic demo flow seed data"
```

Expected: commit succeeds with only the intended files
