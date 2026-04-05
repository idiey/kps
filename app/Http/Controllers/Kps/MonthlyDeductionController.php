<?php

namespace App\Http\Controllers\Kps;

use App\Exports\Kps\SiteBulkTemplateExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Kps\StoreMonthlyDeductionBatchRequest;
use App\Http\Requests\Kps\StoreMonthlyDeductionRequest;
use App\Http\Requests\Kps\StoreSiteBulkUploadRequest;
use App\Imports\Kps\SiteBulkUploadImport;
use App\Models\Kps\MonthlyDeduction;
use App\Models\Kps\Peneroka;
use App\Models\Kps\Site;
use App\Services\Kps\AllocationService;
use App\Services\Kps\MonthlyClosingService;
use App\Services\Kps\SiteContextResolver;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class MonthlyDeductionController extends Controller
{
    public function index(Request $request, Site $site, SiteContextResolver $resolver): Response
    {
        $this->authorize('view', $site);
        $this->authorize('viewAny', MonthlyDeduction::class);

        $month     = $request->get('month');
        $monthDate = $month ? Carbon::createFromFormat('Y-m', $month)->startOfMonth()->toDateString() : Carbon::now()->startOfMonth()->toDateString();
        $selectedMonth = Carbon::parse($monthDate)->format('Y-m');

        $search  = $request->string('search')->trim()->toString();
        $status  = in_array($request->get('status'), ['open', 'closed']) ? $request->get('status') : 'all';
        $sortBy  = in_array($request->get('sort_by'), ['amount', 'peneroka_name']) ? $request->get('sort_by') : null;
        $sortDir = $request->get('sort_dir') === 'asc' ? 'asc' : 'desc';

        $query = MonthlyDeduction::query()
            ->with('peneroka')
            ->where('site_id', $site->id)
            ->whereDate('month', $monthDate)
            ->when($search !== '', fn ($q) =>
                $q->whereHas('peneroka', fn ($p) => $p->where('name', 'like', "%{$search}%"))
            )
            ->when($status === 'open',   fn ($q) => $q->where('is_closed', false))
            ->when($status === 'closed', fn ($q) => $q->where('is_closed', true))
            ->when($sortBy === 'amount',        fn ($q) => $q->orderBy('amount', $sortDir))
            ->when($sortBy === 'peneroka_name', fn ($q) =>
                $q->orderBy(\App\Models\Kps\Peneroka::select('name')->whereColumn('penerokas.id', 'monthly_deductions.peneroka_id')->limit(1), $sortDir)
            )
            ->when($sortBy === null, fn ($q) => $q->orderByDesc('month'));

        $deductions = $query->paginate(15)->withQueryString();

        $summaryQuery = MonthlyDeduction::query()->where('site_id', $site->id)->whereDate('month', $monthDate);
        $summary = [
            'deduction_count'   => (clone $summaryQuery)->count(),
            'total_amount'      => (float) (clone $summaryQuery)->sum('amount'),
            'total_unallocated' => (float) (clone $summaryQuery)->sum('unallocated_amount'),
            'closed_count'      => (clone $summaryQuery)->where('is_closed', true)->count(),
        ];

        $context = $resolver->resolve($request, $site);

        return Inertia::render('Kps/Potongan/Index', [
            'site'          => $site,
            'deductions'    => $deductions,
            'selectedMonth' => $selectedMonth,
            'monthLabel'    => Carbon::parse($monthDate)->format('F Y'),
            'summary'       => $summary,
            'siteRole'      => $context['siteRole'],
            'filters'       => ['search' => $search, 'status' => $status, 'sort_by' => $sortBy ?? '', 'sort_dir' => $sortDir, 'month' => $selectedMonth],
        ]);
    }

    public function create(Request $request, Site $site, SiteContextResolver $resolver): Response
    {
        $this->authorize('create', MonthlyDeduction::class);

        $penerokas = Peneroka::where('site_id', $site->id)
            ->orderBy('name')
            ->get(['id', 'name']);

        $context = $resolver->resolve($request, $site);

        return Inertia::render('Kps/Potongan/Create', [
            'site' => $site,
            'penerokas' => $penerokas,
            'siteRole' => $context['siteRole'],
        ]);
    }

    public function store(
        StoreMonthlyDeductionRequest $request,
        Site $site,
        AllocationService $allocationService,
        MonthlyClosingService $closingService
    ): RedirectResponse {
        $data = $request->validated();

        $peneroka = Peneroka::where('site_id', $site->id)
            ->where('id', $data['peneroka_id'])
            ->firstOrFail();

        $monthDate = Carbon::createFromFormat('Y-m', $data['month'])->startOfMonth()->toDateString();

        if ($closingService->isClosed($site, Carbon::createFromFormat('Y-m', $data['month']))) {
            return back()->withErrors(['month' => 'Month is closed for this site.']);
        }

        $deduction = MonthlyDeduction::query()
            ->where('peneroka_id', $peneroka->id)
            ->whereDate('month', $monthDate)
            ->first();

        if ($deduction) {
            $deduction->fill([
                'site_id' => $site->id,
                'amount' => $data['amount'],
                'unallocated_amount' => 0,
                'is_closed' => false,
                'closed_at' => null,
            ])->save();
        } else {
            $deduction = MonthlyDeduction::create([
                'peneroka_id' => $peneroka->id,
                'site_id' => $site->id,
                'month' => $monthDate,
                'amount' => $data['amount'],
                'unallocated_amount' => 0,
                'is_closed' => false,
                'closed_at' => null,
            ]);
        }

        $allocationService->reallocate($deduction);

        return redirect()->route('kps.potongan.index', $site->id)
            ->with('success', 'Monthly potongan saved and allocated.');
    }

    public function createBulk(Request $request, Site $site, SiteContextResolver $resolver): Response
    {
        $this->authorize('create', MonthlyDeduction::class);

        $penerokas = Peneroka::where('site_id', $site->id)
            ->orderBy('name')
            ->get(['id', 'name']);

        $context = $resolver->resolve($request, $site);

        return Inertia::render('Kps/Potongan/Bulk', [
            'site' => $site,
            'penerokas' => $penerokas,
            'siteRole' => $context['siteRole'],
        ]);
    }

    public function storeBulk(
        StoreMonthlyDeductionBatchRequest $request,
        Site $site,
        AllocationService $allocationService,
        MonthlyClosingService $closingService
    ): RedirectResponse {
        $data = $request->validated();
        $monthDate = Carbon::createFromFormat('Y-m', $data['month'])->startOfMonth()->toDateString();

        if ($closingService->isClosed($site, Carbon::createFromFormat('Y-m', $data['month']))) {
            return back()->withErrors(['month' => 'Month is closed for this site.']);
        }

        foreach ($data['entries'] as $entry) {
            $peneroka = Peneroka::where('site_id', $site->id)
                ->where('id', $entry['peneroka_id'])
                ->first();

            if (!$peneroka) {
                continue;
            }

            $deduction = MonthlyDeduction::query()
                ->where('peneroka_id', $peneroka->id)
                ->whereDate('month', $monthDate)
                ->first();

            if ($deduction) {
                $deduction->fill([
                    'site_id' => $site->id,
                    'amount' => $entry['amount'],
                    'unallocated_amount' => 0,
                    'is_closed' => false,
                    'closed_at' => null,
                ])->save();
            } else {
                $deduction = MonthlyDeduction::create([
                    'peneroka_id' => $peneroka->id,
                    'site_id' => $site->id,
                    'month' => $monthDate,
                    'amount' => $entry['amount'],
                    'unallocated_amount' => 0,
                    'is_closed' => false,
                    'closed_at' => null,
                ]);
            }

            $allocationService->reallocate($deduction);
        }

        return redirect()->route('kps.potongan.index', $site->id)
            ->with('success', 'Bulk potongan saved and allocated.');
    }

    public function downloadBulkTemplate(Request $request, Site $site): BinaryFileResponse
    {
        $this->authorize('view', $site);

        if (! $request->user()->hasPermissionTo('kps.manage_sites')) {
            abort(403);
        }

        $validated = $request->validate([
            'month' => ['nullable', 'date_format:Y-m'],
        ]);

        $month = isset($validated['month'])
            ? Carbon::createFromFormat('Y-m', $validated['month'])->startOfMonth()
            : Carbon::now()->startOfMonth();

        $filename = sprintf(
            '%s-site-bulk-template-%s.xlsx',
            Str::slug($site->code ?: $site->name),
            $month->format('Ym')
        );

        return Excel::download(
            new SiteBulkTemplateExport($site, $month->toDateString()),
            $filename
        );
    }

    public function uploadBulkExcel(
        StoreSiteBulkUploadRequest $request,
        Site $site,
        AllocationService $allocationService,
        MonthlyClosingService $closingService
    ): RedirectResponse {
        $this->authorize('view', $site);

        $data = $request->validated();
        $month = Carbon::createFromFormat('Y-m', $data['month'])->startOfMonth();

        if ($closingService->isClosed($site, $month)) {
            return back()->withErrors(['month' => 'Month is closed for this site.']);
        }

        $import = new SiteBulkUploadImport();
        Excel::import($import, $data['file']);

        if ($import->rows->isEmpty()) {
            return back()->withErrors(['file' => 'Uploaded Excel is empty.']);
        }

        $requiredColumns = collect([
            'peneroka_name',
            'ic_number',
            'phone',
            'address',
            'total_hutang',
            'current_month_dividend',
        ]);

        $firstRow = $import->rows->first();
        $presentColumns = $firstRow instanceof Collection
            ? collect($firstRow->keys())
            : collect(array_keys((array) $firstRow));

        $missingColumns = $requiredColumns->diff($presentColumns);

        if ($missingColumns->isNotEmpty()) {
            return back()->withErrors([
                'file' => 'Template columns are missing: '.$missingColumns->implode(', '),
            ]);
        }

        $result = $this->processBulkUploadRows(
            $site,
            $import->rows,
            $month->toDateString(),
            $allocationService
        );

        return redirect()->route('kps.potongan.index', $site->id)
            ->with(
                'success',
                sprintf(
                    'Excel imported: %d rows processed, %d peneroka updated, %d dividend entries saved.',
                    $result['processed_rows'],
                    $result['updated_peneroka'],
                    $result['saved_deductions']
                )
            );
    }

    private function processBulkUploadRows(
        Site $site,
        Collection $rows,
        string $monthDate,
        AllocationService $allocationService
    ): array {
        $processedRows = 0;
        $updatedPeneroka = 0;
        $deductionsByPeneroka = [];

        foreach ($rows as $row) {
            $rowData = $row instanceof Collection ? $row : collect((array) $row);

            if ($this->isBulkRowEmpty($rowData)) {
                continue;
            }

            $name = $this->normalizeString($rowData->get('peneroka_name'));
            $icNumber = $this->normalizeString($rowData->get('ic_number'));
            $phone = $this->normalizeString($rowData->get('phone'));
            $address = $this->normalizeString($rowData->get('address'));

            if ($name === null && $icNumber === null) {
                continue;
            }

            $peneroka = Peneroka::query()
                ->where('site_id', $site->id)
                ->when(
                    $icNumber !== null,
                    fn ($query) => $query->where('ic_number', $icNumber),
                    fn ($query) => $query->where('name', $name)
                )
                ->first();

            if (! $peneroka) {
                $peneroka = new Peneroka([
                    'site_id' => $site->id,
                ]);
            }

            $peneroka->name = $name ?? $peneroka->name ?? 'Unnamed Peneroka';
            if ($icNumber !== null) {
                $peneroka->ic_number = $icNumber;
            }
            if ($phone !== null) {
                $peneroka->phone = $phone;
            }
            if ($address !== null) {
                $peneroka->address = $address;
            }
            $peneroka->save();

            $updatedPeneroka++;
            $processedRows++;

            $currentMonthDividend = $this->normalizeAmount($rowData->get('current_month_dividend'));
            if ($currentMonthDividend !== null) {
                $deductionsByPeneroka[$peneroka->id] = $currentMonthDividend;
            }
        }

        $savedDeductions = 0;

        foreach ($deductionsByPeneroka as $penerokaId => $amount) {
            $deduction = MonthlyDeduction::query()
                ->where('site_id', $site->id)
                ->where('peneroka_id', $penerokaId)
                ->whereDate('month', $monthDate)
                ->first();

            if ($deduction) {
                $deduction->fill([
                    'amount' => $amount,
                    'unallocated_amount' => 0,
                    'is_closed' => false,
                    'closed_at' => null,
                ])->save();
            } else {
                $deduction = MonthlyDeduction::create([
                    'site_id' => $site->id,
                    'peneroka_id' => $penerokaId,
                    'month' => $monthDate,
                    'amount' => $amount,
                    'unallocated_amount' => 0,
                    'is_closed' => false,
                    'closed_at' => null,
                ]);
            }

            $allocationService->reallocate($deduction);
            $savedDeductions++;
        }

        return [
            'processed_rows' => $processedRows,
            'updated_peneroka' => $updatedPeneroka,
            'saved_deductions' => $savedDeductions,
        ];
    }

    private function isBulkRowEmpty(Collection $row): bool
    {
        $keys = [
            'peneroka_name',
            'ic_number',
            'phone',
            'address',
            'current_month_dividend',
        ];

        foreach ($keys as $key) {
            $value = $row->get($key);
            if ($value !== null && trim((string) $value) !== '') {
                return false;
            }
        }

        return true;
    }

    private function normalizeString(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $string = trim((string) $value);
        return $string === '' ? null : $string;
    }

    private function normalizeAmount(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        $normalized = is_string($value)
            ? str_replace([',', 'RM', 'rm', ' '], '', $value)
            : $value;

        if (! is_numeric($normalized)) {
            return null;
        }

        return round(max(0, (float) $normalized), 2);
    }
}
