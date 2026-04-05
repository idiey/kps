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
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

beforeEach(function () {
    foreach (['kps.manage_sites', 'kps.view', 'kps.view_reports', 'kps.approve_month'] as $permission) {
        Permission::firstOrCreate(['name' => $permission]);
    }

    $this->site = Site::factory()->create();
    $this->peneroka = Peneroka::factory()->create([
        'site_id' => $this->site->id,
    ]);
    $this->debt = Debt::factory()->create([
        'peneroka_id' => $this->peneroka->id,
        'priority' => 1,
        'balance' => 125,
        'original_amount' => 125,
        'due_date' => Carbon::parse('2026-04-20')->toDateString(),
        'description' => 'Hutang Baja',
    ]);
    $this->deduction = MonthlyDeduction::factory()->create([
        'site_id' => $this->site->id,
        'peneroka_id' => $this->peneroka->id,
        'month' => Carbon::parse('2026-04-01')->toDateString(),
        'amount' => 75,
        'unallocated_amount' => 0,
        'is_closed' => false,
    ]);

    app(AllocationService::class)->allocate($this->deduction);

    $this->siteOnlyViewer = User::factory()->create();
    $this->siteOnlyViewer->givePermissionTo('kps.view');
    $this->site->assignUser($this->siteOnlyViewer->id, 'staff');

    $this->reportReader = User::factory()->create();
    $this->reportReader->givePermissionTo(['kps.view', 'kps.view_reports']);
    $this->site->assignUser($this->reportReader->id, 'staff');

    $this->auditReviewer = User::factory()->create();
    $this->auditReviewer->givePermissionTo(['kps.view', 'kps.approve_month']);
    $this->site->assignUser($this->auditReviewer->id, 'site_admin');
});

test('site users need report permission to open the report index', function () {
    $this->actingAs($this->siteOnlyViewer)
        ->get("/kps/sites/{$this->site->id}/reports")
        ->assertForbidden();

    $this->actingAs($this->reportReader)
        ->get("/kps/sites/{$this->site->id}/reports")
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->has('penerokas')
            ->where('penerokas.0.id', $this->peneroka->id)
            ->has('monthLabel')
            ->has('priorityMix')
            ->where('priorityMix.0.priority', 1)
        );
});

test('report readers can open a peneroka statement for their site', function () {
    $this->actingAs($this->reportReader)
        ->get("/kps/sites/{$this->site->id}/reports/peneroka/{$this->peneroka->id}")
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('peneroka.id', $this->peneroka->id)
            ->where('peneroka.debts.0.id', $this->debt->id)
            ->where('deductions.0.id', $this->deduction->id)
            ->where('deductions.0.allocations.0.amount', 75)
        );
});

test('report readers can export site and statement reports', function () {
    $siteCsv = $this->actingAs($this->reportReader)
        ->get("/kps/sites/{$this->site->id}/reports/export/csv");

    $siteCsv
        ->assertOk()
        ->assertHeader('content-type', 'text/csv; charset=UTF-8');

    expect($siteCsv->streamedContent())->toContain('KPS Site Report')
        ->toContain($this->peneroka->name)
        ->toContain($this->site->name);

    $statementCsv = $this->actingAs($this->reportReader)
        ->get("/kps/sites/{$this->site->id}/reports/peneroka/{$this->peneroka->id}/export/csv");

    $statementCsv
        ->assertOk()
        ->assertHeader('content-type', 'text/csv; charset=UTF-8');

    expect($statementCsv->streamedContent())->toContain('KPS Peneroka Statement')
        ->toContain($this->peneroka->name)
        ->toContain('Hutang Baja');

    $pdfResponse = $this->actingAs($this->reportReader)
        ->get("/kps/sites/{$this->site->id}/reports/peneroka/{$this->peneroka->id}/export/pdf");

    $pdfResponse
        ->assertOk()
        ->assertHeader('content-type', 'application/pdf');

    expect($pdfResponse->getContent())->toStartWith('%PDF');
});

test('audit trail requires month approval permission and shows site activity', function () {
    AuditLog::create([
        'site_id' => $this->site->id,
        'user_id' => $this->auditReviewer->id,
        'action' => 'month_closed',
        'auditable_type' => Site::class,
        'auditable_id' => $this->site->id,
        'metadata' => [
            'month' => '2026-04-01',
            'deductions_closed' => 1,
        ],
    ]);

    $this->actingAs($this->reportReader)
        ->get("/kps/sites/{$this->site->id}/audit-logs")
        ->assertForbidden();

    $this->actingAs($this->auditReviewer)
        ->get("/kps/sites/{$this->site->id}/audit-logs")
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('auditLogs.data.0.action', 'month_closed')
            ->where('auditLogs.data.0.metadata.month', '2026-04-01')
            ->where('availableActions.0', 'month_closed')
        );
});
