<?php

use App\Models\Kps\AuditLog;
use App\Models\Kps\Debt;
use App\Models\Kps\MonthlyDeduction;
use App\Models\Kps\Peneroka;
use App\Models\Kps\Site;
use App\Models\User;
use App\Services\Kps\AllocationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

beforeEach(function () {
    foreach (['kps.manage_sites', 'kps.view', 'kps.manage_potongan', 'kps.approve_month'] as $permission) {
        Permission::firstOrCreate(['name' => $permission]);
    }

    $this->site = Site::factory()->create();
    $this->user = User::factory()->create();
    $this->user->givePermissionTo(['kps.view', 'kps.manage_potongan', 'kps.approve_month']);
    $this->site->assignUser($this->user->id, 'staff');

    $this->peneroka = Peneroka::factory()->create([
        'site_id' => $this->site->id,
    ]);

    $this->debtOne = Debt::factory()->create([
        'peneroka_id' => $this->peneroka->id,
        'priority' => 1,
        'balance' => 60,
        'original_amount' => 60,
        'due_date' => Carbon::parse('2026-04-05')->toDateString(),
        'description' => 'Hutang 1',
    ]);
    $this->debtTwo = Debt::factory()->create([
        'peneroka_id' => $this->peneroka->id,
        'priority' => 2,
        'balance' => 80,
        'original_amount' => 80,
        'due_date' => Carbon::parse('2026-04-10')->toDateString(),
        'description' => 'Hutang 2',
    ]);

    $this->month = Carbon::parse('2026-04-01');
});

test('allocation reallocation recalculates using the current debt order', function () {
    $deduction = MonthlyDeduction::factory()->create([
        'site_id' => $this->site->id,
        'peneroka_id' => $this->peneroka->id,
        'month' => $this->month->toDateString(),
        'amount' => 100,
        'unallocated_amount' => 0,
        'is_closed' => false,
    ]);

    app(AllocationService::class)->allocate($deduction);

    $this->debtTwo->update(['priority' => 0]);

    $this->actingAs($this->user)
        ->post("/kps/sites/{$this->site->id}/allocations/{$deduction->id}/reallocate")
        ->assertRedirect();

    $deduction->refresh();

    $amountsByPriority = $deduction->allocations()
        ->with('debt')
        ->get()
        ->mapWithKeys(fn ($allocation) => [$allocation->debt->priority => $allocation->amount])
        ->all();

    ksort($amountsByPriority);

    expect($amountsByPriority)->toBe([
        0 => 80,
        1 => 20,
    ]);
    expect($deduction->unallocated_amount)->toBe(0);
    expect($this->debtOne->fresh()->balance)->toBe(40);
    expect($this->debtTwo->fresh()->balance)->toBe(0);
});

test('closing a month locks deductions and writes an audit log', function () {
    $deductionId = (string) Str::uuid();

    DB::table('monthly_deductions')->insert([
        'id' => $deductionId,
        'site_id' => $this->site->id,
        'peneroka_id' => $this->peneroka->id,
        'month' => $this->month->toDateString(),
        'amount' => 55,
        'unallocated_amount' => 0,
        'is_closed' => false,
        'closed_at' => null,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $deduction = MonthlyDeduction::find($deductionId);

    $this->actingAs($this->user)
        ->post("/kps/sites/{$this->site->id}/allocations/close-month", [
            'month' => '2026-04',
        ])
        ->assertRedirect();

    $deduction->refresh();

    expect($deduction->is_closed)->toBeTrue();
    expect($deduction->closed_at)->not->toBeNull();

    $log = AuditLog::query()
        ->where('site_id', $this->site->id)
        ->where('action', 'month_closed')
        ->first();

    expect($log)->not->toBeNull();
    expect($log->user_id)->toBe($this->user->id);
    expect($log->metadata)->toMatchArray([
        'month' => '2026-04-01',
        'deductions_closed' => 1,
    ]);
});

test('closed months reject new reallocation attempts', function () {
    $deductionId = (string) Str::uuid();

    DB::table('monthly_deductions')->insert([
        'id' => $deductionId,
        'site_id' => $this->site->id,
        'peneroka_id' => $this->peneroka->id,
        'month' => $this->month->toDateString(),
        'amount' => 100,
        'unallocated_amount' => 0,
        'is_closed' => false,
        'closed_at' => null,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $deduction = MonthlyDeduction::find($deductionId);

    app(AllocationService::class)->allocate($deduction);

    $this->actingAs($this->user)
        ->post("/kps/sites/{$this->site->id}/allocations/close-month", [
            'month' => '2026-04',
        ])
        ->assertRedirect();

    $this->actingAs($this->user)
        ->post("/kps/sites/{$this->site->id}/allocations/{$deduction->id}/reallocate")
        ->assertSessionHasErrors('month');
});
