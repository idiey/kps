<?php

use App\Models\Kps\Debt;
use App\Models\Kps\MonthlyDeduction;
use App\Models\Kps\Peneroka;
use App\Models\Kps\Site;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

beforeEach(function () {
    Carbon::setTestNow(Carbon::parse('2026-04-04 09:00:00'));

    foreach ([
        'kps.view',
        'kps.manage_sites',
        'kps.manage_peneroka',
        'kps.manage_hutang',
        'kps.manage_potongan',
        'kps.view_reports',
        'kps.approve_month',
    ] as $permission) {
        Permission::firstOrCreate(['name' => $permission]);
    }

    $this->site = Site::factory()->create([
        'code' => 'FELDA-ST',
    ]);

    $this->user = User::factory()->create();
    $this->user->givePermissionTo(['kps.view', 'kps.view_reports']);
    $this->site->assignUser($this->user->id, 'staff');

    $this->penerokaOne = Peneroka::factory()->create([
        'site_id' => $this->site->id,
        'name' => 'Ahmad bin Abdullah',
        'ic_number' => '650101-01-5678',
        'phone' => '014-107 5150',
    ]);

    $this->penerokaTwo = Peneroka::factory()->create([
        'site_id' => $this->site->id,
        'name' => 'Siti Aisyah',
        'ic_number' => null,
        'phone' => null,
    ]);

    Debt::factory()->create([
        'peneroka_id' => $this->penerokaOne->id,
        'priority' => 2,
        'balance' => 150,
        'original_amount' => 300,
        'due_date' => Carbon::parse('2026-04-20')->toDateString(),
        'description' => 'Hutang Baja',
    ]);

    Debt::factory()->create([
        'peneroka_id' => $this->penerokaTwo->id,
        'priority' => 1,
        'balance' => 75,
        'original_amount' => 100,
        'due_date' => Carbon::parse('2026-05-08')->toDateString(),
        'description' => 'Hutang Benih',
    ]);

    MonthlyDeduction::factory()->create([
        'site_id' => $this->site->id,
        'peneroka_id' => $this->penerokaOne->id,
        'month' => Carbon::parse('2026-04-01')->toDateString(),
        'amount' => 120,
        'unallocated_amount' => 5,
        'is_closed' => false,
    ]);

    MonthlyDeduction::factory()->create([
        'site_id' => $this->site->id,
        'peneroka_id' => $this->penerokaTwo->id,
        'month' => Carbon::parse('2026-03-01')->toDateString(),
        'amount' => 80,
        'unallocated_amount' => 0,
        'is_closed' => true,
    ]);
});

afterEach(function () {
    Carbon::setTestNow();
});

test('site workspace master-data pages expose live summary props', function () {
    $this->actingAs($this->user)
        ->get("/kps/sites/{$this->site->id}/peneroka")
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('summary.total_peneroka', 2)
            ->where('summary.with_ic_number', 1)
            ->where('summary.with_phone', 1)
            ->where('summary.outstanding_total', 225)
        );

    $this->actingAs($this->user)
        ->get("/kps/sites/{$this->site->id}/hutang")
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('summary.total_debts', 2)
            ->where('summary.outstanding_total', 225)
            ->where('summary.due_this_month', 1)
            ->where('summary.highest_priority_open', 1)
        );
});

test('site workspace month-ledger pages default to the current month and scoped summaries', function () {
    $this->actingAs($this->user)
        ->get("/kps/sites/{$this->site->id}/potongan")
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('selectedMonth', '2026-04')
            ->where('monthLabel', 'April 2026')
        );

    $potonganResponse = $this->actingAs($this->user)
        ->get("/kps/sites/{$this->site->id}/potongan?month=2026-04")
        ->assertOk();

    $potonganResponse
        ->assertInertia(fn ($page) => $page
            ->where('selectedMonth', '2026-04')
            ->where('summary.deduction_count', 1)
            ->where('summary.total_amount', 120)
            ->where('summary.total_unallocated', 5)
            ->where('deductions.data.0.month', fn ($value) => str_starts_with((string) $value, '2026-04-01'))
        );

    $this->actingAs($this->user)
        ->get("/kps/sites/{$this->site->id}/allocations")
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('selectedMonth', '2026-04')
            ->where('monthLabel', 'April 2026')
        );

    $this->actingAs($this->user)
        ->get("/kps/sites/{$this->site->id}/allocations?month=2026-04")
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('selectedMonth', '2026-04')
            ->where('summary.deduction_count', 1)
            ->where('summary.total_amount', 120)
            ->where('summary.total_unallocated', 5)
            ->where('deductions.data.0.month', fn ($value) => str_starts_with((string) $value, '2026-04-01'))
        );
});
