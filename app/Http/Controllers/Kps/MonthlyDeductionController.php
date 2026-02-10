<?php

namespace App\Http\Controllers\Kps;

use App\Http\Controllers\Controller;
use App\Http\Requests\Kps\StoreMonthlyDeductionBatchRequest;
use App\Http\Requests\Kps\StoreMonthlyDeductionRequest;
use App\Models\Kps\MonthlyDeduction;
use App\Models\Kps\Peneroka;
use App\Models\Kps\Site;
use App\Services\Kps\AllocationService;
use App\Services\Kps\MonthlyClosingService;
use App\Services\Kps\SiteContextResolver;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MonthlyDeductionController extends Controller
{
    public function index(Request $request, Site $site, SiteContextResolver $resolver): Response
    {
        $this->authorize('view', $site);
        $this->authorize('viewAny', MonthlyDeduction::class);

        $month = $request->get('month');
        $query = MonthlyDeduction::query()
            ->with('peneroka')
            ->where('site_id', $site->id)
            ->orderByDesc('month');

        if ($month) {
            $query->where('month', Carbon::createFromFormat('Y-m', $month)->startOfMonth()->toDateString());
        }

        $deductions = $query->paginate(15)->withQueryString();

        $context = $resolver->resolve($request, $site);

        return Inertia::render('Kps/Potongan/Index', [
            'site' => $site,
            'deductions' => $deductions,
            'selectedMonth' => $month,
            'siteRole' => $context['siteRole'],
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

        $deduction = MonthlyDeduction::updateOrCreate(
            [
                'peneroka_id' => $peneroka->id,
                'month' => $monthDate,
            ],
            [
                'site_id' => $site->id,
                'amount' => $data['amount'],
                'unallocated_amount' => 0,
                'is_closed' => false,
                'closed_at' => null,
            ]
        );

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

            $deduction = MonthlyDeduction::updateOrCreate(
                [
                    'peneroka_id' => $peneroka->id,
                    'month' => $monthDate,
                ],
                [
                    'site_id' => $site->id,
                    'amount' => $entry['amount'],
                    'unallocated_amount' => 0,
                    'is_closed' => false,
                    'closed_at' => null,
                ]
            );

            $allocationService->reallocate($deduction);
        }

        return redirect()->route('kps.potongan.index', $site->id)
            ->with('success', 'Bulk potongan saved and allocated.');
    }
}
