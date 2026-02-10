<?php

namespace App\Http\Controllers\Kps;

use App\Http\Controllers\Controller;
use App\Models\Kps\MonthlyDeduction;
use App\Models\Kps\Site;
use App\Services\Kps\AllocationService;
use App\Services\Kps\MonthlyClosingService;
use App\Services\Kps\SiteContextResolver;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AllocationReviewController extends Controller
{
    public function index(Request $request, Site $site, SiteContextResolver $resolver): Response
    {
        $this->authorize('view', $site);
        $this->authorize('viewAny', MonthlyDeduction::class);

        $month = $request->get('month');
        $query = MonthlyDeduction::query()
            ->with('peneroka')
            ->withCount('allocations')
            ->where('site_id', $site->id)
            ->orderByDesc('month');

        if ($month) {
            $query->where('month', Carbon::createFromFormat('Y-m', $month)->startOfMonth()->toDateString());
        }

        $deductions = $query->paginate(15)->withQueryString();

        $context = $resolver->resolve($request, $site);

        return Inertia::render('Kps/Allocations/Index', [
            'site' => $site,
            'deductions' => $deductions,
            'selectedMonth' => $month,
            'siteRole' => $context['siteRole'],
        ]);
    }

    public function show(Request $request, Site $site, MonthlyDeduction $deduction, SiteContextResolver $resolver): Response
    {
        if ($deduction->site_id !== $site->id) {
            abort(404);
        }

        $this->authorize('view', $deduction);

        $deduction->load(['peneroka', 'allocations.debt']);

        $context = $resolver->resolve($request, $site);

        return Inertia::render('Kps/Allocations/Show', [
            'site' => $site,
            'deduction' => $deduction,
            'siteRole' => $context['siteRole'],
        ]);
    }

    public function reallocate(
        Request $request,
        Site $site,
        MonthlyDeduction $deduction,
        AllocationService $allocationService
    ): RedirectResponse {
        if ($deduction->site_id !== $site->id) {
            abort(404);
        }

        $this->authorize('update', $deduction);

        if ($deduction->is_closed) {
            return back()->withErrors(['month' => 'Month is closed for this site.']);
        }

        $allocationService->reallocate($deduction);

        return back()->with('success', 'Allocations recalculated.');
    }

    public function closeMonth(
        Request $request,
        Site $site,
        MonthlyClosingService $closingService
    ): RedirectResponse {
        if (!$request->user()->hasPermissionTo('kps.approve_month')) {
            abort(403);
        }

        $request->validate([
            'month' => ['required', 'date_format:Y-m'],
        ]);

        $month = Carbon::createFromFormat('Y-m', $request->input('month'));
        $closingService->closeMonth($site, $month, $request->user());

        return back()->with('success', 'Month closed successfully.');
    }
}
