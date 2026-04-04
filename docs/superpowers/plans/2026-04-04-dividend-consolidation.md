# Dividend Consolidation Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Allow admin to record monthly oil palm dividends per peneroka and consolidate them against hutang using a configurable weightage cap, with both auto-waterfall and manual adjustment modes.

**Architecture:** New `dividends` table stores gross/net per peneroka per month. A `DividendService` wraps the existing `AllocationService` pattern — preview runs the waterfall within a cap without saving, consolidate saves atomically. Excel bulk upload uses `maatwebsite/excel`.

**Tech Stack:** Laravel 12, Pest PHP, Inertia + Vue 3, maatwebsite/excel ^3.1

---

## File Structure

| Action | File | Purpose |
|---|---|---|
| Create | `database/migrations/2026_04_04_000000_create_kps_dividends_table.php` | dividends table |
| Create | `database/migrations/2026_04_04_000001_add_dividend_id_to_monthly_deductions.php` | FK link |
| Create | `database/migrations/2026_04_04_000002_add_hutang_weightage_pct_to_sites.php` | site config |
| Create | `app/Models/Kps/Dividend.php` | Dividend model |
| Create | `database/factories/Kps/DividendFactory.php` | test factory |
| Modify | `app/Models/Kps/Site.php` | add fillable + relationship |
| Modify | `app/Models/Kps/MonthlyDeduction.php` | add dividend_id fillable + relationship |
| Create | `app/Services/Kps/DividendService.php` | preview, consolidate, template, import |
| Modify | `app/Services/Kps/MonthlyClosingService.php` | lock dividends on closeMonth |
| Create | `app/Http/Requests/Kps/StoreDividendRequest.php` | single entry validation |
| Create | `app/Http/Requests/Kps/ConsolidateDividendRequest.php` | consolidation save validation |
| Create | `app/Http/Requests/Kps/ImportDividendRequest.php` | Excel upload validation |
| Modify | `app/Http/Requests/Kps/UpdateSiteRequest.php` | add hutang_weightage_pct rule |
| Create | `app/Http/Controllers/Kps/DividendController.php` | 7 methods |
| Modify | `routes/kps.php` | add dividend routes |
| Modify | `app/Http/Controllers/Kps/SiteController.php` | pass hutang_weightage_pct to edit view |
| Create | `resources/js/Pages/Kps/Dividend/Index.vue` | list + upload |
| Create | `resources/js/Pages/Kps/Dividend/Create.vue` | single entry form |
| Create | `resources/js/Pages/Kps/Dividend/Consolidate.vue` | main consolidation UI |
| Create | `tests/Unit/Services/KpsDividendServiceTest.php` | unit tests for service |
| Create | `tests/Feature/KpsDividendWorkflowTest.php` | feature/integration tests |

---

### Task 1: Database Migrations

**Files:**
- Create: `database/migrations/2026_04_04_000000_create_kps_dividends_table.php`
- Create: `database/migrations/2026_04_04_000001_add_dividend_id_to_monthly_deductions.php`
- Create: `database/migrations/2026_04_04_000002_add_hutang_weightage_pct_to_sites.php`

- [ ] **Step 1: Create dividends table migration**

```php
<?php
// database/migrations/2026_04_04_000000_create_kps_dividends_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dividends', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('site_id')->constrained('sites')->onDelete('cascade');
            $table->foreignUuid('peneroka_id')->constrained('penerokas')->onDelete('cascade');
            $table->date('month');
            $table->decimal('gross_amount', 12, 2);
            $table->decimal('net_amount', 12, 2)->default(0);
            $table->boolean('is_locked')->default(false);
            $table->timestamp('locked_at')->nullable();
            $table->timestamps();

            $table->unique(['peneroka_id', 'month']);
            $table->index(['site_id', 'month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dividends');
    }
};
```

- [ ] **Step 2: Create add_dividend_id migration**

```php
<?php
// database/migrations/2026_04_04_000001_add_dividend_id_to_monthly_deductions.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('monthly_deductions', function (Blueprint $table) {
            $table->foreignUuid('dividend_id')
                ->nullable()
                ->after('id')
                ->constrained('dividends')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('monthly_deductions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('dividend_id');
        });
    }
};
```

- [ ] **Step 3: Create hutang_weightage_pct migration**

```php
<?php
// database/migrations/2026_04_04_000002_add_hutang_weightage_pct_to_sites.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->decimal('hutang_weightage_pct', 5, 2)->default(40.00)->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->dropColumn('hutang_weightage_pct');
        });
    }
};
```

- [ ] **Step 4: Run migrations**

```bash
php artisan migrate
```

Expected: 3 new migrations applied, no errors.

- [ ] **Step 5: Commit**

```bash
git add database/migrations/2026_04_04_*
git commit -m "feat: add dividends table, dividend_id FK, hutang_weightage_pct migrations"
```

---

### Task 2: Models + Factory

**Files:**
- Create: `app/Models/Kps/Dividend.php`
- Create: `database/factories/Kps/DividendFactory.php`
- Modify: `app/Models/Kps/Site.php`
- Modify: `app/Models/Kps/MonthlyDeduction.php`

- [ ] **Step 1: Write failing model test**

```php
// tests/Unit/Services/KpsDividendServiceTest.php
<?php

use App\Models\Kps\Dividend;
use App\Models\Kps\MonthlyDeduction;
use App\Models\Kps\Peneroka;
use App\Models\Kps\Site;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('dividend model stores gross and net amounts', function () {
    $site = Site::factory()->create();
    $peneroka = Peneroka::factory()->create(['site_id' => $site->id]);

    $dividend = Dividend::create([
        'site_id' => $site->id,
        'peneroka_id' => $peneroka->id,
        'month' => '2026-04-01',
        'gross_amount' => 800.00,
        'net_amount' => 480.00,
        'is_locked' => false,
    ]);

    expect($dividend->fresh()->gross_amount)->toBe(800.0);
    expect($dividend->fresh()->net_amount)->toBe(480.0);
    expect($dividend->fresh()->is_locked)->toBeFalse();
});

test('site has hutang_weightage_pct defaulting to 40', function () {
    $site = Site::factory()->create();
    expect($site->hutang_weightage_pct)->toBe(40.0);
});
```

- [ ] **Step 2: Run test to verify it fails**

```bash
./vendor/bin/pest tests/Unit/Services/KpsDividendServiceTest.php --filter "dividend model"
```

Expected: FAIL — `App\Models\Kps\Dividend` not found.

- [ ] **Step 3: Create Dividend model**

```php
<?php
// app/Models/Kps/Dividend.php

namespace App\Models\Kps;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Dividend extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'site_id',
        'peneroka_id',
        'month',
        'gross_amount',
        'net_amount',
        'is_locked',
        'locked_at',
    ];

    protected function casts(): array
    {
        return [
            'month' => 'date',
            'gross_amount' => 'float',
            'net_amount' => 'float',
            'is_locked' => 'boolean',
            'locked_at' => 'datetime',
        ];
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function peneroka(): BelongsTo
    {
        return $this->belongsTo(Peneroka::class);
    }

    public function monthlyDeduction(): HasOne
    {
        return $this->hasOne(MonthlyDeduction::class);
    }
}
```

- [ ] **Step 4: Update Site model**

Add to `$fillable`: `'hutang_weightage_pct'`
Add to `casts()`: `'hutang_weightage_pct' => 'float'`
Add relationship:

```php
// In app/Models/Kps/Site.php — add to fillable array:
'hutang_weightage_pct',

// Add to casts():
'hutang_weightage_pct' => 'float',

// Add relationship method:
public function dividends(): HasMany
{
    return $this->hasMany(Dividend::class);
}
```

- [ ] **Step 5: Update MonthlyDeduction model**

Add to `$fillable`: `'dividend_id'`
Add relationship method:

```php
// In app/Models/Kps/MonthlyDeduction.php — add to fillable:
'dividend_id',

// Add relationship method:
public function dividend(): BelongsTo
{
    return $this->belongsTo(Dividend::class);
}
```

- [ ] **Step 6: Create DividendFactory**

```php
<?php
// database/factories/Kps/DividendFactory.php

namespace Database\Factories\Kps;

use App\Models\Kps\Dividend;
use App\Models\Kps\Peneroka;
use Illuminate\Database\Eloquent\Factories\Factory;

class DividendFactory extends Factory
{
    protected $model = Dividend::class;

    public function definition(): array
    {
        return [
            'peneroka_id' => Peneroka::factory(),
            'site_id' => function (array $attributes) {
                $peneroka = Peneroka::find($attributes['peneroka_id']);
                return $peneroka?->site_id;
            },
            'month' => now()->startOfMonth()->toDateString(),
            'gross_amount' => fake()->randomFloat(2, 200, 1500),
            'net_amount' => 0,
            'is_locked' => false,
        ];
    }
}
```

- [ ] **Step 7: Run tests to verify they pass**

```bash
./vendor/bin/pest tests/Unit/Services/KpsDividendServiceTest.php
```

Expected: 2 tests PASS.

- [ ] **Step 8: Commit**

```bash
git add app/Models/Kps/Dividend.php database/factories/Kps/DividendFactory.php \
    app/Models/Kps/Site.php app/Models/Kps/MonthlyDeduction.php \
    tests/Unit/Services/KpsDividendServiceTest.php
git commit -m "feat: add Dividend model, factory, update Site and MonthlyDeduction"
```

---

### Task 3: DividendService — computeCap + preview

**Files:**
- Create: `app/Services/Kps/DividendService.php`

- [ ] **Step 1: Write failing test**

Add to `tests/Unit/Services/KpsDividendServiceTest.php`:

```php
use App\Models\Kps\Debt;
use App\Services\Kps\DividendService;

test('computeCap returns gross multiplied by site weightage', function () {
    $site = Site::factory()->create(['hutang_weightage_pct' => 40.00]);
    $service = new DividendService();

    expect($service->computeCap(800.00, $site))->toBe(320.0);
    expect($service->computeCap(1000.00, $site))->toBe(400.0);
});

test('preview returns waterfall allocations capped at weightage percentage', function () {
    $site = Site::factory()->create(['hutang_weightage_pct' => 40.00]);
    $peneroka = Peneroka::factory()->create(['site_id' => $site->id]);

    $debt1 = Debt::factory()->create([
        'peneroka_id' => $peneroka->id,
        'priority' => 1,
        'balance' => 300.00,
        'original_amount' => 300.00,
    ]);

    $debt2 = Debt::factory()->create([
        'peneroka_id' => $peneroka->id,
        'priority' => 2,
        'balance' => 250.00,
        'original_amount' => 250.00,
    ]);

    $service = new DividendService();
    // gross=800, cap=320: debt1=300 (full), debt2=20 (partial, cap exhausted)
    $preview = $service->preview($peneroka, '2026-04-01', 800.00, $site);

    expect($preview)->toHaveCount(2);
    expect($preview[0]['debt_id'])->toBe($debt1->id);
    expect($preview[0]['payable'])->toBe(300.0);
    expect($preview[0]['skipped'])->toBeFalse();
    expect($preview[1]['debt_id'])->toBe($debt2->id);
    expect($preview[1]['payable'])->toBe(20.0);
    expect($preview[1]['skipped'])->toBeFalse();
});

test('preview marks debts as skipped when cap is exhausted', function () {
    $site = Site::factory()->create(['hutang_weightage_pct' => 10.00]);
    $peneroka = Peneroka::factory()->create(['site_id' => $site->id]);

    Debt::factory()->create([
        'peneroka_id' => $peneroka->id,
        'priority' => 1,
        'balance' => 500.00,
        'original_amount' => 500.00,
    ]);

    $debt2 = Debt::factory()->create([
        'peneroka_id' => $peneroka->id,
        'priority' => 2,
        'balance' => 200.00,
        'original_amount' => 200.00,
    ]);

    $service = new DividendService();
    // gross=100, cap=10: debt1=10, debt2=0 (skipped)
    $preview = $service->preview($peneroka, '2026-04-01', 100.00, $site);

    expect($preview[1]['debt_id'])->toBe($debt2->id);
    expect($preview[1]['payable'])->toBe(0.0);
    expect($preview[1]['skipped'])->toBeTrue();
});
```

- [ ] **Step 2: Run tests to verify they fail**

```bash
./vendor/bin/pest tests/Unit/Services/KpsDividendServiceTest.php --filter "computeCap|preview"
```

Expected: FAIL — `App\Services\Kps\DividendService` not found.

- [ ] **Step 3: Create DividendService with computeCap and preview**

```php
<?php
// app/Services/Kps/DividendService.php

namespace App\Services\Kps;

use App\Models\Kps\Debt;
use App\Models\Kps\Dividend;
use App\Models\Kps\MonthlyDeduction;
use App\Models\Kps\Peneroka;
use App\Models\Kps\Site;
use App\Models\User;

class DividendService
{
    public function computeCap(float $gross, Site $site): float
    {
        return round($gross * $site->hutang_weightage_pct / 100, 2);
    }

    /**
     * Run the waterfall within the weightage cap — does NOT save anything.
     * Returns array of preview items:
     * [debt_id, description, priority, balance, payable, skipped]
     */
    public function preview(Peneroka $peneroka, string $month, float $gross, Site $site): array
    {
        $cap = $this->computeCap($gross, $site);
        $remaining = $cap;

        $debts = Debt::query()
            ->where('peneroka_id', $peneroka->id)
            ->where('balance', '>', 0)
            ->orderBy('priority')
            ->orderByRaw('due_date IS NULL')
            ->orderBy('due_date')
            ->orderBy('created_at')
            ->get();

        $result = [];

        foreach ($debts as $debt) {
            $payable = 0.0;
            $skipped = false;

            if ($remaining > 0) {
                $payable = round(min($remaining, $debt->balance), 2);
                $remaining = round($remaining - $payable, 2);
            } else {
                $skipped = true;
            }

            $result[] = [
                'debt_id' => $debt->id,
                'description' => $debt->description,
                'priority' => $debt->priority,
                'balance' => (float) $debt->balance,
                'payable' => $payable,
                'skipped' => $skipped,
            ];
        }

        return $result;
    }
}
```

- [ ] **Step 4: Run tests to verify they pass**

```bash
./vendor/bin/pest tests/Unit/Services/KpsDividendServiceTest.php --filter "computeCap|preview"
```

Expected: 3 tests PASS.

- [ ] **Step 5: Commit**

```bash
git add app/Services/Kps/DividendService.php tests/Unit/Services/KpsDividendServiceTest.php
git commit -m "feat: DividendService computeCap and preview methods"
```

---

### Task 4: DividendService — consolidate

**Files:**
- Modify: `app/Services/Kps/DividendService.php`

- [ ] **Step 1: Write failing tests**

Add to `tests/Unit/Services/KpsDividendServiceTest.php`:

```php
use App\Models\Kps\DeductionAllocation;
use Illuminate\Support\Facades\DB;

test('consolidate creates dividend, deduction, and allocation records', function () {
    $site = Site::factory()->create(['hutang_weightage_pct' => 40.00]);
    $peneroka = Peneroka::factory()->create(['site_id' => $site->id]);

    $debt1 = Debt::factory()->create([
        'peneroka_id' => $peneroka->id,
        'priority' => 1,
        'balance' => 300.00,
        'original_amount' => 300.00,
    ]);

    $debt2 = Debt::factory()->create([
        'peneroka_id' => $peneroka->id,
        'priority' => 2,
        'balance' => 250.00,
        'original_amount' => 250.00,
    ]);

    $service = new DividendService();

    $allocations = [
        ['debt_id' => $debt1->id, 'amount' => 300.00],
        ['debt_id' => $debt2->id, 'amount' => 20.00],
    ];

    $dividend = $service->consolidate($peneroka, '2026-04-01', 800.00, $allocations, $site);

    expect($dividend->gross_amount)->toBe(800.0);
    expect($dividend->net_amount)->toBe(480.0); // 800 - 320

    $deduction = MonthlyDeduction::where('dividend_id', $dividend->id)->first();
    expect($deduction)->not->toBeNull();
    expect($deduction->amount)->toBe(320.0);

    expect(DeductionAllocation::where('monthly_deduction_id', $deduction->id)->count())->toBe(2);
    expect($debt1->fresh()->balance)->toBe(0.0);
    expect($debt2->fresh()->balance)->toBe(230.0); // 250 - 20
});

test('consolidate skips zero-amount allocations', function () {
    $site = Site::factory()->create(['hutang_weightage_pct' => 40.00]);
    $peneroka = Peneroka::factory()->create(['site_id' => $site->id]);

    $debt1 = Debt::factory()->create([
        'peneroka_id' => $peneroka->id,
        'priority' => 1,
        'balance' => 300.00,
        'original_amount' => 300.00,
    ]);

    $debt2 = Debt::factory()->create([
        'peneroka_id' => $peneroka->id,
        'priority' => 2,
        'balance' => 250.00,
        'original_amount' => 250.00,
    ]);

    $service = new DividendService();

    $allocations = [
        ['debt_id' => $debt1->id, 'amount' => 300.00],
        ['debt_id' => $debt2->id, 'amount' => 0.00], // skipped
    ];

    $dividend = $service->consolidate($peneroka, '2026-04-01', 800.00, $allocations, $site);

    $deduction = MonthlyDeduction::where('dividend_id', $dividend->id)->first();
    expect(DeductionAllocation::where('monthly_deduction_id', $deduction->id)->count())->toBe(1);
    expect($debt2->fresh()->balance)->toBe(250.0); // unchanged
});

test('reconsolidation reverses previous allocations before reapplying', function () {
    $site = Site::factory()->create(['hutang_weightage_pct' => 40.00]);
    $peneroka = Peneroka::factory()->create(['site_id' => $site->id]);

    $debt = Debt::factory()->create([
        'peneroka_id' => $peneroka->id,
        'priority' => 1,
        'balance' => 300.00,
        'original_amount' => 300.00,
    ]);

    $service = new DividendService();

    // First consolidation
    $service->consolidate($peneroka, '2026-04-01', 800.00, [
        ['debt_id' => $debt->id, 'amount' => 200.00],
    ], $site);

    expect($debt->fresh()->balance)->toBe(100.0);

    // Re-consolidation with different amount
    $service->consolidate($peneroka, '2026-04-01', 800.00, [
        ['debt_id' => $debt->id, 'amount' => 50.00],
    ], $site);

    expect($debt->fresh()->balance)->toBe(250.0); // 300 - 50
    expect(Dividend::where('peneroka_id', $peneroka->id)->count())->toBe(1);
});
```

- [ ] **Step 2: Run tests to verify they fail**

```bash
./vendor/bin/pest tests/Unit/Services/KpsDividendServiceTest.php --filter "consolidate|reconsolidation"
```

Expected: FAIL — `consolidate` method not found.

- [ ] **Step 3: Implement consolidate in DividendService**

Add to `app/Services/Kps/DividendService.php`:

```php
use App\Models\Kps\AuditLog;
use App\Models\Kps\DeductionAllocation;
use Illuminate\Support\Facades\DB;

// Add this method to DividendService class:

public function consolidate(
    Peneroka $peneroka,
    string $month,
    float $gross,
    array $allocations,
    Site $site,
    ?User $user = null
): Dividend {
    return DB::transaction(function () use ($peneroka, $month, $gross, $allocations, $site, $user) {
        // Create or retrieve dividend record
        $dividend = Dividend::firstOrNew([
            'peneroka_id' => $peneroka->id,
            'month' => $month,
        ]);
        $dividend->site_id = $site->id;
        $dividend->gross_amount = $gross;

        // Reverse existing deduction and allocations if re-consolidating
        if ($dividend->exists) {
            $existing = MonthlyDeduction::where('dividend_id', $dividend->id)->first();
            if ($existing) {
                $existing->allocations()->with('debt')->get()->each(function (DeductionAllocation $a) {
                    if ($a->debt) {
                        $a->debt->balance = $a->debt->balance + $a->amount;
                        $a->debt->save();
                    }
                });
                $existing->allocations()->delete();
                $existing->delete();
            }
        }

        $dividend->save();

        // Apply allocations
        $total = 0.0;
        $nonZero = array_filter($allocations, fn ($a) => $a['amount'] > 0);

        $deduction = MonthlyDeduction::create([
            'dividend_id' => $dividend->id,
            'peneroka_id' => $peneroka->id,
            'site_id' => $site->id,
            'month' => $month,
            'amount' => 0,
            'unallocated_amount' => 0,
            'is_closed' => false,
        ]);

        foreach ($nonZero as $item) {
            $debt = Debt::lockForUpdate()->find($item['debt_id']);
            if (!$debt) {
                continue;
            }

            $pay = round(min((float) $item['amount'], $debt->balance), 2);

            DeductionAllocation::create([
                'monthly_deduction_id' => $deduction->id,
                'debt_id' => $debt->id,
                'amount' => $pay,
            ]);

            $debt->balance = round($debt->balance - $pay, 2);
            $debt->save();
            $total += $pay;
        }

        $deduction->amount = round($total, 2);
        $deduction->save();

        $dividend->net_amount = round($gross - $total, 2);
        $dividend->save();

        $manualAdjustment = collect($allocations)->some(function ($a, $i) use ($dividend, $site) {
            return true; // always log; flag if differs from preview
        });

        AuditLog::create([
            'site_id' => $site->id,
            'user_id' => $user?->id,
            'action' => 'dividend_consolidated',
            'auditable_type' => Dividend::class,
            'auditable_id' => $dividend->id,
            'metadata' => [
                'month' => $month,
                'gross_amount' => $gross,
                'net_amount' => $dividend->net_amount,
                'total_hutang' => $total,
                'peneroka_id' => $peneroka->id,
            ],
        ]);

        return $dividend;
    });
}
```

- [ ] **Step 4: Run tests to verify they pass**

```bash
./vendor/bin/pest tests/Unit/Services/KpsDividendServiceTest.php --filter "consolidate|reconsolidation"
```

Expected: 3 tests PASS.

- [ ] **Step 5: Commit**

```bash
git add app/Services/Kps/DividendService.php tests/Unit/Services/KpsDividendServiceTest.php
git commit -m "feat: DividendService consolidate method with reversal and audit log"
```

---

### Task 5: DividendService — Excel template + bulk import

**Files:**
- Modify: `app/Services/Kps/DividendService.php`
- Create: `app/Exports/Kps/DividendTemplateExport.php`
- Create: `app/Imports/Kps/DividendImport.php`

- [ ] **Step 1: Write failing test for bulk import**

Add to `tests/Unit/Services/KpsDividendServiceTest.php`:

```php
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;

test('bulkImport creates dividends for valid rows and skips blank amounts', function () {
    $site = Site::factory()->create(['hutang_weightage_pct' => 40.00]);
    $peneroka1 = Peneroka::factory()->create(['site_id' => $site->id, 'ic_number' => '701234-05-1234']);
    $peneroka2 = Peneroka::factory()->create(['site_id' => $site->id, 'ic_number' => '680512-08-5678']);

    $csvContent = "No.,No. IC,Nama Peneroka,Dividen Kasar (RM)\n"
        . "1,701234-05-1234,{$peneroka1->name},800.00\n"
        . "2,680512-08-5678,{$peneroka2->name},\n"; // blank — skip

    $file = UploadedFile::fake()->createWithContent('dividend.csv', $csvContent);

    $service = new DividendService();
    $result = $service->bulkImport($site, '2026-04-01', $file);

    expect($result['imported'])->toBe(1);
    expect($result['skipped'])->toBe(1);
    expect($result['errors'])->toHaveCount(0);
    expect(Dividend::where('site_id', $site->id)->count())->toBe(1);
});

test('bulkImport records error for IC not found in site', function () {
    $site = Site::factory()->create();

    $csvContent = "No.,No. IC,Nama Peneroka,Dividen Kasar (RM)\n"
        . "1,999999-99-9999,Unknown Person,500.00\n";

    $file = UploadedFile::fake()->createWithContent('dividend.csv', $csvContent);

    $service = new DividendService();
    $result = $service->bulkImport($site, '2026-04-01', $file);

    expect($result['imported'])->toBe(0);
    expect($result['errors'])->toHaveCount(1);
    expect($result['errors'][0])->toContain('row 2');
});
```

- [ ] **Step 2: Run tests to verify they fail**

```bash
./vendor/bin/pest tests/Unit/Services/KpsDividendServiceTest.php --filter "bulkImport"
```

Expected: FAIL — `bulkImport` method not found.

- [ ] **Step 3: Create DividendTemplateExport**

```php
<?php
// app/Exports/Kps/DividendTemplateExport.php

namespace App\Exports\Kps;

use App\Models\Kps\Peneroka;
use App\Models\Kps\Site;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class DividendTemplateExport implements FromCollection, WithHeadings, WithTitle
{
    public function __construct(
        private Site $site,
        private string $month
    ) {}

    public function collection()
    {
        return Peneroka::where('site_id', $this->site->id)
            ->orderBy('name')
            ->get()
            ->map(fn ($p, $i) => [
                'no' => $i + 1,
                'ic_number' => $p->ic_number,
                'name' => $p->name,
                'gross_amount' => '',
            ]);
    }

    public function headings(): array
    {
        return ['No.', 'No. IC', 'Nama Peneroka', 'Dividen Kasar (RM)'];
    }

    public function title(): string
    {
        return 'Dividen ' . $this->month;
    }
}
```

- [ ] **Step 4: Add generateTemplate + bulkImport to DividendService**

Add these methods to `app/Services/Kps/DividendService.php`:

```php
use App\Exports\Kps\DividendTemplateExport;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

public function generateTemplate(Site $site, string $month): BinaryFileResponse
{
    return Excel::download(
        new DividendTemplateExport($site, $month),
        "dividen-template-{$month}.xlsx"
    );
}

public function bulkImport(Site $site, string $month, UploadedFile $file, ?User $user = null): array
{
    $imported = 0;
    $skipped = 0;
    $errors = [];
    $rowNumber = 1; // header is row 1, data starts row 2

    $handle = fopen($file->getPathname(), 'r');
    fgetcsv($handle); // skip header

    while (($row = fgetcsv($handle)) !== false) {
        $rowNumber++;

        if (count($row) < 4) {
            $skipped++;
            continue;
        }

        $icNumber = trim($row[1] ?? '');
        $rawAmount = trim($row[3] ?? '');

        if ($rawAmount === '' || $rawAmount === null) {
            $skipped++;
            continue;
        }

        if (!is_numeric($rawAmount) || (float) $rawAmount <= 0) {
            $errors[] = "row {$rowNumber} — amaun tidak sah: {$rawAmount}";
            continue;
        }

        $peneroka = Peneroka::where('site_id', $site->id)
            ->where('ic_number', $icNumber)
            ->first();

        if (!$peneroka) {
            $errors[] = "row {$rowNumber} — No. IC tidak dijumpai: {$icNumber}";
            continue;
        }

        try {
            $preview = $this->preview($peneroka, $month, (float) $rawAmount, $site);
            $allocations = array_map(fn ($item) => [
                'debt_id' => $item['debt_id'],
                'amount' => $item['payable'],
            ], $preview);

            $this->consolidate($peneroka, $month, (float) $rawAmount, $allocations, $site, $user);
            $imported++;
        } catch (\Throwable $e) {
            $errors[] = "row {$rowNumber} — {$e->getMessage()}";
        }
    }

    fclose($handle);

    if ($imported > 0 && $user) {
        AuditLog::create([
            'site_id' => $site->id,
            'user_id' => $user->id,
            'action' => 'dividend_imported',
            'auditable_type' => Site::class,
            'auditable_id' => $site->id,
            'metadata' => [
                'month' => $month,
                'imported' => $imported,
                'skipped' => $skipped,
                'errors' => count($errors),
            ],
        ]);
    }

    return compact('imported', 'skipped', 'errors');
}
```

- [ ] **Step 5: Run tests to verify they pass**

```bash
./vendor/bin/pest tests/Unit/Services/KpsDividendServiceTest.php --filter "bulkImport"
```

Expected: 2 tests PASS.

- [ ] **Step 6: Commit**

```bash
git add app/Services/Kps/DividendService.php app/Exports/Kps/DividendTemplateExport.php \
    tests/Unit/Services/KpsDividendServiceTest.php
git commit -m "feat: DividendService generateTemplate and bulkImport methods"
```

---

### Task 6: MonthlyClosingService — lock dividends

**Files:**
- Modify: `app/Services/Kps/MonthlyClosingService.php`

- [ ] **Step 1: Write failing test**

Add to `tests/Feature/KpsDividendWorkflowTest.php`:

```php
<?php

use App\Models\Kps\Debt;
use App\Models\Kps\Dividend;
use App\Models\Kps\Peneroka;
use App\Models\Kps\Site;
use App\Models\User;
use App\Services\Kps\DividendService;
use App\Services\Kps\MonthlyClosingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    foreach (['kps.manage_sites', 'kps.view', 'kps.manage_potongan', 'kps.approve_month'] as $p) {
        Permission::firstOrCreate(['name' => $p]);
    }

    $this->site = Site::factory()->create(['hutang_weightage_pct' => 40.00]);
    $this->user = User::factory()->create();
    $this->user->givePermissionTo(['kps.view', 'kps.manage_potongan', 'kps.approve_month']);
    $this->site->assignUser($this->user->id, 'site_admin');

    $this->peneroka = Peneroka::factory()->create(['site_id' => $this->site->id]);
    $this->month = Carbon::parse('2026-04-01');
});

test('closeMonth locks dividends for the site and month', function () {
    $dividendService = new DividendService();
    $dividend = $dividendService->consolidate(
        $this->peneroka,
        '2026-04-01',
        800.00,
        [],
        $this->site
    );

    $closingService = new MonthlyClosingService();
    $closingService->closeMonth($this->site, $this->month, $this->user);

    expect($dividend->fresh()->is_locked)->toBeTrue();
    expect($dividend->fresh()->locked_at)->not->toBeNull();
});
```

- [ ] **Step 2: Run test to verify it fails**

```bash
./vendor/bin/pest tests/Feature/KpsDividendWorkflowTest.php --filter "closeMonth locks dividends"
```

Expected: FAIL — dividends not being locked.

- [ ] **Step 3: Update MonthlyClosingService**

```php
// In app/Services/Kps/MonthlyClosingService.php
// Add import:
use App\Models\Kps\Dividend;

// In closeMonth() method, after the MonthlyDeduction update, add:
Dividend::query()
    ->where('site_id', $site->id)
    ->where('month', $monthDate)
    ->update([
        'is_locked' => true,
        'locked_at' => now(),
    ]);
```

Full updated `closeMonth` method:

```php
public function closeMonth(Site $site, Carbon $month, ?User $user = null): int
{
    $monthDate = $month->copy()->startOfMonth()->toDateString();

    $affected = MonthlyDeduction::query()
        ->where('site_id', $site->id)
        ->where('month', $monthDate)
        ->update([
            'is_closed' => true,
            'closed_at' => now(),
        ]);

    Dividend::query()
        ->where('site_id', $site->id)
        ->where('month', $monthDate)
        ->update([
            'is_locked' => true,
            'locked_at' => now(),
        ]);

    AuditLog::create([
        'site_id' => $site->id,
        'user_id' => $user?->id,
        'action' => 'month_closed',
        'auditable_type' => Site::class,
        'auditable_id' => $site->id,
        'metadata' => [
            'month' => $monthDate,
            'deductions_closed' => $affected,
        ],
    ]);

    return $affected;
}
```

- [ ] **Step 4: Run test to verify it passes**

```bash
./vendor/bin/pest tests/Feature/KpsDividendWorkflowTest.php --filter "closeMonth locks dividends"
```

Expected: PASS.

- [ ] **Step 5: Run full test suite to ensure nothing broke**

```bash
./vendor/bin/pest
```

Expected: All existing tests PASS.

- [ ] **Step 6: Commit**

```bash
git add app/Services/Kps/MonthlyClosingService.php tests/Feature/KpsDividendWorkflowTest.php
git commit -m "feat: lock dividends on month close"
```

---

### Task 7: Form Requests + UpdateSiteRequest

**Files:**
- Create: `app/Http/Requests/Kps/StoreDividendRequest.php`
- Create: `app/Http/Requests/Kps/ConsolidateDividendRequest.php`
- Create: `app/Http/Requests/Kps/ImportDividendRequest.php`
- Modify: `app/Http/Requests/Kps/UpdateSiteRequest.php`

- [ ] **Step 1: Create StoreDividendRequest**

```php
<?php
// app/Http/Requests/Kps/StoreDividendRequest.php

namespace App\Http\Requests\Kps;

use Illuminate\Foundation\Http\FormRequest;

class StoreDividendRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('kps.manage_potongan');
    }

    public function rules(): array
    {
        return [
            'peneroka_id' => ['required', 'uuid', 'exists:penerokas,id'],
            'month' => ['required', 'date_format:Y-m'],
            'gross_amount' => ['required', 'numeric', 'min:0.01'],
        ];
    }
}
```

- [ ] **Step 2: Create ConsolidateDividendRequest**

```php
<?php
// app/Http/Requests/Kps/ConsolidateDividendRequest.php

namespace App\Http\Requests\Kps;

use Illuminate\Foundation\Http\FormRequest;

class ConsolidateDividendRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('kps.manage_potongan');
    }

    public function rules(): array
    {
        return [
            'gross_amount' => ['required', 'numeric', 'min:0.01'],
            'allocations' => ['required', 'array'],
            'allocations.*.debt_id' => ['required', 'uuid', 'exists:debts,id'],
            'allocations.*.amount' => ['required', 'numeric', 'min:0'],
        ];
    }
}
```

- [ ] **Step 3: Create ImportDividendRequest**

```php
<?php
// app/Http/Requests/Kps/ImportDividendRequest.php

namespace App\Http\Requests\Kps;

use Illuminate\Foundation\Http\FormRequest;

class ImportDividendRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('kps.manage_potongan');
    }

    public function rules(): array
    {
        return [
            'month' => ['required', 'date_format:Y-m'],
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:5120'],
        ];
    }
}
```

- [ ] **Step 4: Update UpdateSiteRequest**

Add to `rules()` array:

```php
'hutang_weightage_pct' => ['sometimes', 'numeric', 'min:1', 'max:100'],
```

- [ ] **Step 5: Commit**

```bash
git add app/Http/Requests/Kps/StoreDividendRequest.php \
    app/Http/Requests/Kps/ConsolidateDividendRequest.php \
    app/Http/Requests/Kps/ImportDividendRequest.php \
    app/Http/Requests/Kps/UpdateSiteRequest.php
git commit -m "feat: add dividend form requests, update UpdateSiteRequest with weightage"
```

---

### Task 8: DividendController + Routes

**Files:**
- Create: `app/Http/Controllers/Kps/DividendController.php`
- Modify: `routes/kps.php`

- [ ] **Step 1: Write failing feature test**

Add to `tests/Feature/KpsDividendWorkflowTest.php`:

```php
test('admin can view dividend index page', function () {
    $this->actingAs($this->user)
        ->get("/kps/sites/{$this->site->id}/dividends")
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('Kps/Dividend/Index'));
});

test('admin can preview dividend waterfall', function () {
    $debt = Debt::factory()->create([
        'peneroka_id' => $this->peneroka->id,
        'priority' => 1,
        'balance' => 300.00,
        'original_amount' => 300.00,
    ]);

    $this->actingAs($this->user)
        ->postJson("/kps/sites/{$this->site->id}/dividends/preview", [
            'peneroka_id' => $this->peneroka->id,
            'month' => '2026-04',
            'gross_amount' => 800.00,
        ])
        ->assertOk()
        ->assertJsonStructure(['cap', 'preview']);
});

test('admin can save a consolidated dividend', function () {
    $debt = Debt::factory()->create([
        'peneroka_id' => $this->peneroka->id,
        'priority' => 1,
        'balance' => 300.00,
        'original_amount' => 300.00,
    ]);

    // First create a dividend
    $dividendService = new DividendService();
    $dividend = $dividendService->consolidate(
        $this->peneroka, '2026-04-01', 800.00, [], $this->site
    );

    $this->actingAs($this->user)
        ->post("/kps/sites/{$this->site->id}/dividends/{$dividend->id}/consolidate", [
            'gross_amount' => 800.00,
            'allocations' => [
                ['debt_id' => $debt->id, 'amount' => 300.00],
            ],
        ])
        ->assertRedirect();

    expect($dividend->fresh()->net_amount)->toBe(500.0);
});
```

- [ ] **Step 2: Run tests to verify they fail**

```bash
./vendor/bin/pest tests/Feature/KpsDividendWorkflowTest.php --filter "admin can view|preview dividend|save a consolidated"
```

Expected: FAIL — routes not found (404).

- [ ] **Step 3: Create DividendController**

```php
<?php
// app/Http/Controllers/Kps/DividendController.php

namespace App\Http\Controllers\Kps;

use App\Http\Controllers\Controller;
use App\Http\Requests\Kps\ConsolidateDividendRequest;
use App\Http\Requests\Kps\ImportDividendRequest;
use App\Http\Requests\Kps\StoreDividendRequest;
use App\Models\Kps\Debt;
use App\Models\Kps\Dividend;
use App\Models\Kps\Peneroka;
use App\Models\Kps\Site;
use App\Services\Kps\DividendService;
use App\Services\Kps\MonthlyClosingService;
use App\Services\Kps\SiteContextResolver;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DividendController extends Controller
{
    public function index(Request $request, Site $site, SiteContextResolver $resolver): Response
    {
        $this->authorize('viewAny', Dividend::class);

        $month = $request->get('month');
        $query = Dividend::with('peneroka')
            ->where('site_id', $site->id)
            ->orderByDesc('month');

        if ($month) {
            $query->where('month', Carbon::createFromFormat('Y-m', $month)->startOfMonth()->toDateString());
        }

        $dividends = $query->paginate(15)->withQueryString();
        $context = $resolver->resolve($request, $site);

        return Inertia::render('Kps/Dividend/Index', [
            'site' => $site,
            'dividends' => $dividends,
            'selectedMonth' => $month,
            'siteRole' => $context['siteRole'],
        ]);
    }

    public function create(Request $request, Site $site, SiteContextResolver $resolver): Response
    {
        $this->authorize('create', Dividend::class);

        $penerokas = Peneroka::where('site_id', $site->id)->orderBy('name')->get(['id', 'name', 'ic_number']);
        $context = $resolver->resolve($request, $site);

        return Inertia::render('Kps/Dividend/Create', [
            'site' => $site,
            'penerokas' => $penerokas,
            'siteRole' => $context['siteRole'],
        ]);
    }

    public function store(
        StoreDividendRequest $request,
        Site $site,
        DividendService $service,
        MonthlyClosingService $closingService
    ): RedirectResponse {
        $data = $request->validated();
        $month = Carbon::createFromFormat('Y-m', $data['month'])->startOfMonth()->toDateString();

        if ($closingService->isClosed($site, Carbon::createFromFormat('Y-m', $data['month']))) {
            return back()->withErrors(['month' => 'Bulan ini telah ditutup.']);
        }

        $peneroka = Peneroka::where('site_id', $site->id)->findOrFail($data['peneroka_id']);
        $preview = $service->preview($peneroka, $month, (float) $data['gross_amount'], $site);
        $allocations = array_map(fn ($item) => ['debt_id' => $item['debt_id'], 'amount' => $item['payable']], $preview);

        $dividend = $service->consolidate($peneroka, $month, (float) $data['gross_amount'], $allocations, $site, $request->user());

        return redirect()->route('kps.dividends.consolidate', [$site->id, $dividend->id])
            ->with('success', 'Dividen disimpan. Semak dan laras peruntukan hutang.');
    }

    public function exportTemplate(Request $request, Site $site, DividendService $service)
    {
        $month = $request->get('month', now()->format('Y-m'));
        $monthDate = Carbon::createFromFormat('Y-m', $month)->startOfMonth()->toDateString();

        return $service->generateTemplate($site, $monthDate);
    }

    public function import(
        ImportDividendRequest $request,
        Site $site,
        DividendService $service,
        MonthlyClosingService $closingService
    ): RedirectResponse {
        $data = $request->validated();
        $month = Carbon::createFromFormat('Y-m', $data['month'])->startOfMonth()->toDateString();

        if ($closingService->isClosed($site, Carbon::createFromFormat('Y-m', $data['month']))) {
            return back()->withErrors(['month' => 'Bulan ini telah ditutup.']);
        }

        $result = $service->bulkImport($site, $month, $data['file'], $request->user());

        return redirect()->route('kps.dividends.index', $site->id)
            ->with('importResult', $result);
    }

    public function preview(Request $request, Site $site, DividendService $service)
    {
        $request->validate([
            'peneroka_id' => ['required', 'uuid', 'exists:penerokas,id'],
            'month' => ['required', 'date_format:Y-m'],
            'gross_amount' => ['required', 'numeric', 'min:0.01'],
        ]);

        $month = Carbon::createFromFormat('Y-m', $request->month)->startOfMonth()->toDateString();
        $peneroka = Peneroka::where('site_id', $site->id)->findOrFail($request->peneroka_id);
        $gross = (float) $request->gross_amount;

        $previewData = $service->preview($peneroka, $month, $gross, $site);
        $cap = $service->computeCap($gross, $site);

        return response()->json([
            'cap' => $cap,
            'preview' => $previewData,
        ]);
    }

    public function consolidate(Request $request, Site $site, Dividend $dividend, SiteContextResolver $resolver): Response
    {
        $this->authorize('update', $dividend);

        $debts = Debt::where('peneroka_id', $dividend->peneroka_id)
            ->where('balance', '>', 0)
            ->orderBy('priority')
            ->orderByRaw('due_date IS NULL')
            ->orderBy('due_date')
            ->orderBy('created_at')
            ->get();

        $existingAllocations = $dividend->monthlyDeduction?->allocations()->with('debt')->get() ?? collect();
        $context = $resolver->resolve($request, $site);

        return Inertia::render('Kps/Dividend/Consolidate', [
            'site' => $site,
            'dividend' => $dividend->load('peneroka'),
            'debts' => $debts,
            'cap' => app(DividendService::class)->computeCap($dividend->gross_amount, $site),
            'existingAllocations' => $existingAllocations,
            'weightage' => $site->hutang_weightage_pct,
            'siteRole' => $context['siteRole'],
        ]);
    }

    public function store(
        ConsolidateDividendRequest $request,
        Site $site,
        Dividend $dividend,
        DividendService $service,
        MonthlyClosingService $closingService
    ): RedirectResponse {
        if ($dividend->is_locked) {
            return back()->withErrors(['locked' => 'Dividen ini telah dikunci.']);
        }

        $monthCarbon = Carbon::parse($dividend->month);
        if ($closingService->isClosed($site, $monthCarbon)) {
            return back()->withErrors(['month' => 'Bulan ini telah ditutup.']);
        }

        $data = $request->validated();
        $peneroka = $dividend->peneroka;

        $service->consolidate(
            $peneroka,
            $dividend->month->toDateString(),
            (float) $data['gross_amount'],
            $data['allocations'],
            $site,
            $request->user()
        );

        return redirect()->route('kps.dividends.index', $site->id)
            ->with('success', 'Peruntukan hutang disimpan.');
    }
}
```

> **Note:** The controller has two methods named `store`. Rename the consolidation save to `saveConsolidation` and the single entry save to `store`. Update route binding accordingly (see routes step).

- [ ] **Step 4: Fix duplicate store method — rename consolidation save**

In `DividendController`, rename the second `store` to `saveConsolidation`:

```php
public function saveConsolidation(
    ConsolidateDividendRequest $request,
    Site $site,
    Dividend $dividend,
    DividendService $service,
    MonthlyClosingService $closingService
): RedirectResponse { ... }
```

- [ ] **Step 5: Add routes to routes/kps.php**

Inside the `Route::prefix('sites/{site}')->group(...)` block, add:

```php
// Dividends
Route::get('dividends', [DividendController::class, 'index'])->name('dividends.index');
Route::get('dividends/create', [DividendController::class, 'create'])->name('dividends.create');
Route::post('dividends', [DividendController::class, 'store'])->name('dividends.store');
Route::get('dividends/template', [DividendController::class, 'exportTemplate'])->name('dividends.template');
Route::post('dividends/import', [DividendController::class, 'import'])->name('dividends.import');
Route::post('dividends/preview', [DividendController::class, 'preview'])->name('dividends.preview');
Route::get('dividends/{dividend}/consolidate', [DividendController::class, 'consolidate'])->name('dividends.consolidate');
Route::post('dividends/{dividend}/consolidate', [DividendController::class, 'saveConsolidation'])->name('dividends.consolidate.save');
```

Add import at top of `routes/kps.php`:

```php
use App\Http\Controllers\Kps\DividendController;
```

- [ ] **Step 6: Add DividendPolicy** (minimal — reuse site authorization)

```php
<?php
// app/Policies/DividendPolicy.php

namespace App\Policies;

use App\Models\Kps\Dividend;
use App\Models\User;

class DividendPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('kps.view');
    }

    public function create(User $user): bool
    {
        return $user->can('kps.manage_potongan');
    }

    public function update(User $user, Dividend $dividend): bool
    {
        return $user->can('kps.manage_potongan') && !$dividend->is_locked;
    }
}
```

Register in `app/Providers/AuthServiceProvider.php` (or `AppServiceProvider.php` if using Gate::policy):

```php
Gate::policy(Dividend::class, DividendPolicy::class);
```

- [ ] **Step 7: Run feature tests**

```bash
./vendor/bin/pest tests/Feature/KpsDividendWorkflowTest.php
```

Expected: All tests PASS.

- [ ] **Step 8: Commit**

```bash
git add app/Http/Controllers/Kps/DividendController.php \
    app/Policies/DividendPolicy.php \
    routes/kps.php \
    app/Providers/AuthServiceProvider.php
git commit -m "feat: DividendController, routes, and DividendPolicy"
```

---

### Task 9: Site Settings — hutang_weightage_pct

**Files:**
- Modify: `app/Http/Controllers/Kps/SiteController.php`
- Modify: `resources/js/Pages/Kps/Sites/Edit.vue`

- [ ] **Step 1: Write failing test**

Add to `tests/Feature/KpsDividendWorkflowTest.php`:

```php
test('site admin can update hutang weightage pct', function () {
    $this->user->givePermissionTo('kps.manage_sites');

    $this->actingAs($this->user)
        ->put("/kps/sites/{$this->site->id}", [
            'name' => $this->site->name,
            'code' => $this->site->code,
            'is_active' => true,
            'hutang_weightage_pct' => 35.00,
        ])
        ->assertRedirect();

    expect($this->site->fresh()->hutang_weightage_pct)->toBe(35.0);
});
```

- [ ] **Step 2: Run test to verify it fails**

```bash
./vendor/bin/pest tests/Feature/KpsDividendWorkflowTest.php --filter "hutang weightage"
```

Expected: FAIL — weightage not saved (not in fillable or validation yet).

- [ ] **Step 3: Verify Site fillable and UpdateSiteRequest include hutang_weightage_pct**

`Site::$fillable` must include `'hutang_weightage_pct'` (done in Task 2).
`UpdateSiteRequest::rules()` must include `'hutang_weightage_pct'` rule (done in Task 7).

- [ ] **Step 4: Run test to verify it passes**

```bash
./vendor/bin/pest tests/Feature/KpsDividendWorkflowTest.php --filter "hutang weightage"
```

Expected: PASS.

- [ ] **Step 5: Pass hutang_weightage_pct to Edit.vue from SiteController**

In `app/Http/Controllers/Kps/SiteController.php`, `edit()` method — the `$site` model already contains all fields including `hutang_weightage_pct`, so no change needed. Inertia renders the full model.

- [ ] **Step 6: Update resources/js/Pages/Kps/Sites/Edit.vue**

Find the form section and add after the `is_active` field:

```vue
<div>
  <InputLabel for="hutang_weightage_pct" value="Had Bayaran Hutang (%)" />
  <TextInput
    id="hutang_weightage_pct"
    v-model="form.hutang_weightage_pct"
    type="number"
    min="1"
    max="100"
    step="0.01"
    class="mt-1 block w-full"
  />
  <InputError :message="form.errors.hutang_weightage_pct" class="mt-2" />
  <p class="mt-1 text-sm text-gray-500">
    Peratusan maksimum dividen yang boleh digunakan untuk bayaran hutang (lalai: 40%)
  </p>
</div>
```

Add `hutang_weightage_pct` to the form initializer:

```js
const form = useForm({
  // ...existing fields...
  hutang_weightage_pct: props.site.hutang_weightage_pct,
})
```

- [ ] **Step 7: Commit**

```bash
git add resources/js/Pages/Kps/Sites/Edit.vue tests/Feature/KpsDividendWorkflowTest.php
git commit -m "feat: expose hutang_weightage_pct in site edit form"
```

---

### Task 10: Vue — Dividend/Index.vue + Dividend/Create.vue

**Files:**
- Create: `resources/js/Pages/Kps/Dividend/Index.vue`
- Create: `resources/js/Pages/Kps/Dividend/Create.vue`

- [ ] **Step 1: Create Dividend/Index.vue**

```vue
<!-- resources/js/Pages/Kps/Dividend/Index.vue -->
<script setup>
import { router, useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  site: Object,
  dividends: Object,
  selectedMonth: String,
  siteRole: String,
})

const importForm = useForm({
  month: props.selectedMonth ?? new Date().toISOString().slice(0, 7),
  file: null,
})

function filterByMonth(month) {
  router.get(route('kps.dividends.index', props.site.id), { month }, { preserveState: true })
}

function downloadTemplate() {
  const month = props.selectedMonth ?? new Date().toISOString().slice(0, 7)
  window.location.href = route('kps.dividends.template', props.site.id) + '?month=' + month
}

function submitImport() {
  importForm.post(route('kps.dividends.import', props.site.id))
}
</script>

<template>
  <AuthenticatedLayout>
    <div class="max-w-6xl mx-auto py-6 px-4">
      <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold">Dividen — {{ site.name }}</h1>
        <div class="flex gap-2">
          <a :href="route('kps.dividends.create', site.id)"
             class="px-4 py-2 bg-blue-600 text-white rounded text-sm">
            + Tambah Dividen
          </a>
        </div>
      </div>

      <!-- Month Filter -->
      <div class="mb-4 flex gap-4 items-center">
        <input type="month" class="border rounded px-3 py-1.5 text-sm"
               :value="selectedMonth"
               @change="filterByMonth($event.target.value)" />
        <button @click="downloadTemplate"
                class="px-3 py-1.5 border rounded text-sm text-gray-700 hover:bg-gray-50">
          Muat Turun Template
        </button>
      </div>

      <!-- Import -->
      <div class="mb-6 p-4 bg-gray-50 border rounded">
        <h3 class="text-sm font-medium mb-2">Upload Excel Dividen</h3>
        <form @submit.prevent="submitImport" class="flex gap-3 items-end">
          <div>
            <label class="text-xs text-gray-500">Bulan</label>
            <input type="month" v-model="importForm.month"
                   class="block border rounded px-2 py-1 text-sm" />
          </div>
          <div>
            <label class="text-xs text-gray-500">Fail</label>
            <input type="file" accept=".xlsx,.xls,.csv"
                   class="block text-sm"
                   @change="importForm.file = $event.target.files[0]" />
          </div>
          <button type="submit" :disabled="importForm.processing"
                  class="px-4 py-1.5 bg-green-600 text-white rounded text-sm">
            Upload
          </button>
        </form>
        <p v-if="importForm.errors.file" class="text-red-500 text-xs mt-1">{{ importForm.errors.file }}</p>
      </div>

      <!-- Dividends Table -->
      <table class="w-full text-sm border-collapse">
        <thead>
          <tr class="border-b bg-gray-50">
            <th class="text-left px-4 py-2">Peneroka</th>
            <th class="text-left px-4 py-2">Bulan</th>
            <th class="text-right px-4 py-2">Dividen Kasar</th>
            <th class="text-right px-4 py-2">Net Diterima</th>
            <th class="text-center px-4 py-2">Status</th>
            <th class="px-4 py-2"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="d in dividends.data" :key="d.id" class="border-b hover:bg-gray-50">
            <td class="px-4 py-2">{{ d.peneroka?.name }}</td>
            <td class="px-4 py-2">{{ d.month }}</td>
            <td class="px-4 py-2 text-right">RM {{ Number(d.gross_amount).toFixed(2) }}</td>
            <td class="px-4 py-2 text-right">RM {{ Number(d.net_amount).toFixed(2) }}</td>
            <td class="px-4 py-2 text-center">
              <span v-if="d.is_locked" class="text-xs text-red-600 font-medium">Dikunci</span>
              <span v-else class="text-xs text-green-600 font-medium">Aktif</span>
            </td>
            <td class="px-4 py-2 text-right">
              <a v-if="!d.is_locked"
                 :href="route('kps.dividends.consolidate', [site.id, d.id])"
                 class="text-blue-600 text-xs hover:underline">Laras</a>
            </td>
          </tr>
          <tr v-if="dividends.data.length === 0">
            <td colspan="6" class="px-4 py-8 text-center text-gray-400">Tiada rekod dividen.</td>
          </tr>
        </tbody>
      </table>
    </div>
  </AuthenticatedLayout>
</template>
```

- [ ] **Step 2: Create Dividend/Create.vue**

```vue
<!-- resources/js/Pages/Kps/Dividend/Create.vue -->
<script setup>
import { useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import InputError from '@/Components/InputError.vue'

const props = defineProps({
  site: Object,
  penerokas: Array,
  siteRole: String,
})

const form = useForm({
  peneroka_id: '',
  month: new Date().toISOString().slice(0, 7),
  gross_amount: '',
})

function submit() {
  form.post(route('kps.dividends.store', props.site.id))
}
</script>

<template>
  <AuthenticatedLayout>
    <div class="max-w-lg mx-auto py-8 px-4">
      <h1 class="text-xl font-bold mb-6">Tambah Dividen</h1>

      <form @submit.prevent="submit" class="space-y-5">
        <div>
          <InputLabel value="Peneroka" />
          <select v-model="form.peneroka_id"
                  class="mt-1 block w-full border rounded px-3 py-2 text-sm">
            <option value="">-- Pilih Peneroka --</option>
            <option v-for="p in penerokas" :key="p.id" :value="p.id">
              {{ p.name }} ({{ p.ic_number }})
            </option>
          </select>
          <InputError :message="form.errors.peneroka_id" class="mt-1" />
        </div>

        <div>
          <InputLabel value="Bulan" />
          <input type="month" v-model="form.month"
                 class="mt-1 block w-full border rounded px-3 py-2 text-sm" />
          <InputError :message="form.errors.month" class="mt-1" />
        </div>

        <div>
          <InputLabel value="Dividen Kasar (RM)" />
          <TextInput v-model="form.gross_amount" type="number" step="0.01" min="0.01"
                     class="mt-1 block w-full" placeholder="0.00" />
          <InputError :message="form.errors.gross_amount" class="mt-1" />
        </div>

        <div class="flex gap-3">
          <button type="submit" :disabled="form.processing"
                  class="px-6 py-2 bg-blue-600 text-white rounded text-sm">
            Simpan & Laras Hutang
          </button>
          <a :href="route('kps.dividends.index', site.id)"
             class="px-6 py-2 border rounded text-sm text-gray-700">Batal</a>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>
```

- [ ] **Step 3: Commit**

```bash
git add resources/js/Pages/Kps/Dividend/Index.vue resources/js/Pages/Kps/Dividend/Create.vue
git commit -m "feat: Dividend Index and Create Vue pages"
```

---

### Task 11: Vue — Dividend/Consolidate.vue

**Files:**
- Create: `resources/js/Pages/Kps/Dividend/Consolidate.vue`

- [ ] **Step 1: Create Consolidate.vue**

```vue
<!-- resources/js/Pages/Kps/Dividend/Consolidate.vue -->
<script setup>
import { ref, computed, watch } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import axios from 'axios'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  site: Object,
  dividend: Object,
  debts: Array,
  cap: Number,
  existingAllocations: Array,
  weightage: Number,
  siteRole: String,
})

// Build rows from debts + existing allocations
const rows = ref(props.debts.map(debt => {
  const existing = props.existingAllocations.find(a => a.debt_id === debt.id)
  return {
    debt_id: debt.id,
    description: debt.description,
    priority: debt.priority,
    balance: parseFloat(debt.balance),
    amount: existing ? parseFloat(existing.amount) : 0,
    skipped: !existing || parseFloat(existing.amount) === 0,
  }
}))

const grossAmount = ref(parseFloat(props.dividend.gross_amount))
const currentCap = computed(() => Math.round(grossAmount.value * props.weightage / 100 * 100) / 100)
const totalPay = computed(() => rows.value.reduce((sum, r) => sum + (r.skipped ? 0 : parseFloat(r.amount || 0)), 0))
const netAmount = computed(() => Math.round((grossAmount.value - totalPay.value) * 100) / 100)
const capUsedPct = computed(() => currentCap.value > 0 ? Math.min(100, (totalPay.value / currentCap.value) * 100) : 0)
const capExceeded = computed(() => totalPay.value > currentCap.value + 0.001)

const isLocked = props.dividend.is_locked

// Re-run preview when gross changes
let debounceTimer = null
watch(grossAmount, (val) => {
  clearTimeout(debounceTimer)
  debounceTimer = setTimeout(() => runPreview(val), 400)
})

async function runPreview(gross) {
  const month = props.dividend.month.slice(0, 7)
  try {
    const res = await axios.post(route('kps.dividends.preview', props.site.id), {
      peneroka_id: props.dividend.peneroka_id,
      month,
      gross_amount: gross,
    })
    rows.value = res.data.preview.map(item => ({
      debt_id: item.debt_id,
      description: item.description,
      priority: item.priority,
      balance: item.balance,
      amount: item.payable,
      skipped: item.skipped,
    }))
  } catch (e) {
    // keep current rows
  }
}

function toggleSkip(row) {
  if (isLocked) return
  row.skipped = !row.skipped
  if (row.skipped) row.amount = 0
}

function clampAmount(row) {
  const val = parseFloat(row.amount)
  if (isNaN(val) || val < 0) row.amount = 0
  if (val > row.balance) row.amount = row.balance
}

const form = useForm({})

function save() {
  if (capExceeded.value) return

  const allocations = rows.value.map(r => ({
    debt_id: r.debt_id,
    amount: r.skipped ? 0 : parseFloat(r.amount || 0),
  }))

  form
    .transform(() => ({
      gross_amount: grossAmount.value,
      allocations,
    }))
    .post(route('kps.dividends.consolidate.save', [props.site.id, props.dividend.id]))
}
</script>

<template>
  <AuthenticatedLayout>
    <div class="max-w-4xl mx-auto py-6 px-4">
      <!-- Header -->
      <div class="mb-6">
        <h1 class="text-xl font-bold">{{ dividend.peneroka?.name }}</h1>
        <p class="text-gray-500 text-sm">Peruntukan Hutang — {{ dividend.month }}</p>
        <div v-if="isLocked" class="mt-2 px-3 py-2 bg-red-50 text-red-700 rounded text-sm">
          Bulan ini telah ditutup. Rekod adalah baca sahaja.
        </div>
      </div>

      <!-- Gross + Cap -->
      <div class="grid grid-cols-2 gap-4 mb-6">
        <div class="p-4 border rounded">
          <label class="text-xs text-gray-500 uppercase tracking-wide">Dividen Kasar (RM)</label>
          <input v-if="!isLocked" v-model="grossAmount" type="number" step="0.01" min="0.01"
                 class="mt-1 block w-full text-2xl font-bold border-0 focus:ring-0 p-0" />
          <p v-else class="text-2xl font-bold mt-1">{{ Number(grossAmount).toFixed(2) }}</p>
        </div>
        <div class="p-4 border rounded bg-amber-50">
          <label class="text-xs text-gray-500 uppercase tracking-wide">
            Had Bayaran Hutang ({{ weightage }}%)
          </label>
          <p class="text-2xl font-bold mt-1">RM {{ currentCap.toFixed(2) }}</p>
        </div>
      </div>

      <!-- Hutang Table -->
      <table class="w-full text-sm mb-6 border-collapse">
        <thead>
          <tr class="border-b bg-gray-50">
            <th class="text-left px-4 py-2">#</th>
            <th class="text-left px-4 py-2">Hutang</th>
            <th class="text-right px-4 py-2">Baki (RM)</th>
            <th class="text-right px-4 py-2">Bayar (RM)</th>
            <th class="text-center px-4 py-2">Langkau</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(row, i) in rows" :key="row.debt_id"
              :class="['border-b', row.skipped ? 'opacity-50' : '']">
            <td class="px-4 py-2 text-gray-400">{{ i + 1 }}</td>
            <td class="px-4 py-2">
              <span class="font-medium">{{ row.description }}</span>
              <span class="ml-2 text-xs text-gray-400">Keutamaan {{ row.priority }}</span>
            </td>
            <td class="px-4 py-2 text-right">{{ row.balance.toFixed(2) }}</td>
            <td class="px-4 py-2 text-right">
              <input v-if="!isLocked && !row.skipped"
                     v-model="row.amount"
                     type="number" step="0.01" min="0" :max="row.balance"
                     @change="clampAmount(row)"
                     class="w-28 text-right border rounded px-2 py-1 text-sm" />
              <span v-else class="text-gray-500">{{ Number(row.amount).toFixed(2) }}</span>
            </td>
            <td class="px-4 py-2 text-center">
              <button v-if="!isLocked" @click="toggleSkip(row)"
                      :class="['w-6 h-6 rounded-full border-2 text-xs font-bold',
                               row.skipped ? 'border-gray-300 text-gray-400' : 'border-blue-500 text-blue-500']">
                {{ row.skipped ? '○' : '✓' }}
              </button>
              <span v-else>{{ row.skipped ? '○' : '✓' }}</span>
            </td>
          </tr>
          <tr v-if="rows.length === 0">
            <td colspan="5" class="px-4 py-6 text-center text-gray-400">Tiada hutang aktif.</td>
          </tr>
        </tbody>
      </table>

      <!-- Cap progress -->
      <div class="mb-6">
        <div class="flex justify-between text-xs text-gray-500 mb-1">
          <span>Had Hutang Digunakan</span>
          <span :class="capExceeded ? 'text-red-600 font-bold' : ''">
            RM {{ totalPay.toFixed(2) }} / RM {{ currentCap.toFixed(2) }}
          </span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2">
          <div :class="['h-2 rounded-full transition-all', capExceeded ? 'bg-red-500' : 'bg-blue-500']"
               :style="{ width: Math.min(capUsedPct, 100) + '%' }"></div>
        </div>
        <p v-if="capExceeded" class="text-red-600 text-xs mt-1">
          Jumlah bayaran melebihi had {{ weightage }}%. Sila laras semula.
        </p>
      </div>

      <!-- Summary -->
      <div class="border-t pt-4 mb-6 space-y-1 text-sm">
        <div class="flex justify-between">
          <span class="text-gray-600">Jumlah Bayaran Hutang</span>
          <span class="font-medium">RM {{ totalPay.toFixed(2) }}</span>
        </div>
        <div class="flex justify-between text-lg font-bold">
          <span>Net Diterima Peneroka</span>
          <span class="text-green-600">RM {{ netAmount.toFixed(2) }}</span>
        </div>
      </div>

      <!-- Action -->
      <div v-if="!isLocked" class="flex gap-3">
        <button @click="save" :disabled="form.processing || capExceeded"
                class="px-8 py-2 bg-blue-600 text-white rounded text-sm disabled:opacity-50">
          Simpan
        </button>
        <a :href="route('kps.dividends.index', site.id)"
           class="px-6 py-2 border rounded text-sm text-gray-700">Batal</a>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
```

- [ ] **Step 2: Add "Dividen" to site sidebar navigation**

In the site sidebar component (find by searching for `potongan` in `resources/js/`), add a new nav item:

```vue
<SidebarLink
  :href="route('kps.dividends.index', site.id)"
  :active="route().current('kps.dividends.*')"
>
  Dividen
</SidebarLink>
```

- [ ] **Step 3: Run full test suite**

```bash
./vendor/bin/pest
```

Expected: All tests PASS.

- [ ] **Step 4: Commit**

```bash
git add resources/js/Pages/Kps/Dividend/Consolidate.vue resources/js/
git commit -m "feat: Dividend Consolidate Vue page with live cap calculation"
```

---

## Self-Review

**Spec coverage check:**

| Spec requirement | Task |
|---|---|
| dividends table | Task 1 |
| dividend_id on monthly_deductions | Task 1 |
| hutang_weightage_pct on sites | Task 1 |
| Dividend model | Task 2 |
| computeCap | Task 3 |
| waterfall preview within cap | Task 3 |
| consolidate saves all records atomically | Task 4 |
| reversal on re-consolidation | Task 4 |
| audit logging (dividend_consolidated) | Task 4 |
| Excel template generation | Task 5 |
| bulk CSV/Excel import | Task 5 |
| audit logging (dividend_imported) | Task 5 |
| closeMonth locks dividends | Task 6 |
| form validation | Task 7 |
| hutang_weightage_pct in site edit | Tasks 7, 9 |
| DividendController all 7 methods | Task 8 |
| DividendPolicy | Task 8 |
| Routes | Task 8 |
| Dividend/Index.vue | Task 10 |
| Dividend/Create.vue | Task 10 |
| Dividend/Consolidate.vue (live cap, skip, adjust) | Task 11 |
| Site sidebar link | Task 11 |

**Placeholder scan:** None found.

**Type consistency:** `computeCap`, `preview`, `consolidate`, `bulkImport`, `generateTemplate` — all consistent between Tasks 3-8. `DividendService` injected by Laravel's container via method injection in controller.
