<?php

use App\Models\Kps\Debt;
use App\Models\Kps\MonthlyDeduction;
use App\Models\Kps\Peneroka;
use App\Models\Kps\Site;
use App\Services\Kps\AllocationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('it allocates by priority due date and created at', function () {
    $service = new AllocationService();

    $site = Site::factory()->create();
    $peneroka = Peneroka::factory()->create(['site_id' => $site->id]);

    $debt1 = Debt::factory()->create([
        'peneroka_id' => $peneroka->id,
        'priority' => 1,
        'due_date' => Carbon::parse('2026-01-05'),
        'balance' => 100,
        'original_amount' => 100,
        'created_at' => Carbon::parse('2026-01-01 00:00:00'),
    ]);

    $debt2 = Debt::factory()->create([
        'peneroka_id' => $peneroka->id,
        'priority' => 1,
        'due_date' => Carbon::parse('2026-01-05'),
        'balance' => 50,
        'original_amount' => 50,
        'created_at' => Carbon::parse('2026-01-02 00:00:00'),
    ]);

    $debt3 = Debt::factory()->create([
        'peneroka_id' => $peneroka->id,
        'priority' => 1,
        'due_date' => null,
        'balance' => 30,
        'original_amount' => 30,
        'created_at' => Carbon::parse('2026-01-03 00:00:00'),
    ]);

    $debt4 = Debt::factory()->create([
        'peneroka_id' => $peneroka->id,
        'priority' => 2,
        'due_date' => Carbon::parse('2026-01-01'),
        'balance' => 20,
        'original_amount' => 20,
        'created_at' => Carbon::parse('2026-01-01 00:00:00'),
    ]);

    $deduction = MonthlyDeduction::factory()->create([
        'peneroka_id' => $peneroka->id,
        'site_id' => $site->id,
        'amount' => 200,
        'unallocated_amount' => 0,
    ]);

    $service->allocate($deduction);

    $allocationOrder = $deduction->allocations()->pluck('debt_id')->toArray();

    expect($allocationOrder)->toBe([
        $debt1->id,
        $debt2->id,
        $debt3->id,
        $debt4->id,
    ]);

    expect($deduction->fresh()->unallocated_amount)->toBe(0);
});

test('it tracks unallocated amount and updates balance', function () {
    $service = new AllocationService();

    $site = Site::factory()->create();
    $peneroka = Peneroka::factory()->create(['site_id' => $site->id]);

    $debt = Debt::factory()->create([
        'peneroka_id' => $peneroka->id,
        'priority' => 1,
        'balance' => 100,
        'original_amount' => 100,
    ]);

    $deduction = MonthlyDeduction::factory()->create([
        'peneroka_id' => $peneroka->id,
        'site_id' => $site->id,
        'amount' => 150,
        'unallocated_amount' => 0,
    ]);

    $service->allocate($deduction);

    expect($debt->fresh()->balance)->toBe(0);
    expect($deduction->fresh()->unallocated_amount)->toBe(50);
});
