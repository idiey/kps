<?php

namespace App\Http\Controllers\Kps;

use App\Http\Controllers\Controller;
use App\Models\Kps\Debt;
use App\Models\Kps\MonthlyDeduction;
use App\Models\Kps\Peneroka;
use App\Models\Kps\Site;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AnalyticsController extends Controller
{
    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Site::class);

        $month = now()->startOfMonth();
        $monthDate = $month->toDateString();
        $trendStart = $month->copy()->subMonths(5);

        $totalSites = Site::count();
        $activeSites = Site::active()->count();
        $totalPeneroka = Peneroka::count();
        $totalDebts = Debt::count();
        $totalOutstanding = (float) Debt::sum('balance');
        $totalOriginalAmount = (float) Debt::sum('original_amount');
        $currentMonthDeductions = (float) MonthlyDeduction::whereDate('month', $monthDate)->sum('amount');
        $currentMonthUnallocated = (float) MonthlyDeduction::whereDate('month', $monthDate)->sum('unallocated_amount');
        $currentMonthClosedCount = MonthlyDeduction::whereDate('month', $monthDate)->where('is_closed', true)->count();
        $currentMonthOpenCount = MonthlyDeduction::whereDate('month', $monthDate)->where('is_closed', false)->count();
        $sitesWithActivity = MonthlyDeduction::whereDate('month', $monthDate)->distinct()->count('site_id');

        $overview = [
            'total_sites' => $totalSites,
            'active_sites' => $activeSites,
            'sites_with_activity' => $sitesWithActivity,
            'total_peneroka' => $totalPeneroka,
            'total_debts' => $totalDebts,
            'total_outstanding' => $totalOutstanding,
            'total_original_amount' => $totalOriginalAmount,
            'current_month_deductions' => $currentMonthDeductions,
            'current_month_allocated' => max(0, $currentMonthDeductions - $currentMonthUnallocated),
            'current_month_unallocated' => $currentMonthUnallocated,
            'current_month_closed_count' => $currentMonthClosedCount,
            'current_month_open_count' => $currentMonthOpenCount,
            'allocation_rate' => $currentMonthDeductions > 0
                ? round((($currentMonthDeductions - $currentMonthUnallocated) / $currentMonthDeductions) * 100, 1)
                : 0.0,
            'average_outstanding_per_peneroka' => $totalPeneroka > 0
                ? round($totalOutstanding / $totalPeneroka, 2)
                : 0.0,
        ];

        $sitePenerokaCounts = Peneroka::query()
            ->selectRaw('site_id, COUNT(*) as peneroka_count')
            ->groupBy('site_id')
            ->pluck('peneroka_count', 'site_id');

        $siteDebtStats = Debt::query()
            ->join('penerokas', 'debts.peneroka_id', '=', 'penerokas.id')
            ->selectRaw('penerokas.site_id as site_id, COUNT(debts.id) as debt_count, SUM(CASE WHEN debts.balance > 0 THEN 1 ELSE 0 END) as active_debt_count, SUM(debts.balance) as outstanding, SUM(debts.original_amount) as original_amount')
            ->groupBy('penerokas.site_id')
            ->get()
            ->keyBy('site_id');

        $siteMonthlyStats = MonthlyDeduction::query()
            ->selectRaw('site_id, COUNT(*) as deduction_count, SUM(amount) as amount, SUM(unallocated_amount) as unallocated_amount, SUM(CASE WHEN is_closed = 1 THEN 1 ELSE 0 END) as closed_count')
            ->whereDate('month', $monthDate)
            ->groupBy('site_id')
            ->get()
            ->keyBy('site_id');

        $sitePerformance = Site::query()
            ->orderBy('name')
            ->get(['id', 'name', 'code', 'is_active'])
            ->map(function (Site $site) use ($sitePenerokaCounts, $siteDebtStats, $siteMonthlyStats) {
                $debtStats = $siteDebtStats->get($site->id);
                $monthlyStats = $siteMonthlyStats->get($site->id);

                return [
                    'id' => $site->id,
                    'name' => $site->name,
                    'code' => $site->code,
                    'is_active' => (bool) $site->is_active,
                    'peneroka_count' => (int) ($sitePenerokaCounts[$site->id] ?? 0),
                    'debt_count' => (int) ($debtStats->debt_count ?? 0),
                    'active_debt_count' => (int) ($debtStats->active_debt_count ?? 0),
                    'outstanding' => (float) ($debtStats->outstanding ?? 0),
                    'original_amount' => (float) ($debtStats->original_amount ?? 0),
                    'monthly_deductions' => (float) ($monthlyStats->amount ?? 0),
                    'monthly_unallocated' => (float) ($monthlyStats->unallocated_amount ?? 0),
                    'monthly_closed_count' => (int) ($monthlyStats->closed_count ?? 0),
                    'monthly_deduction_count' => (int) ($monthlyStats->deduction_count ?? 0),
                ];
            })
            ->sortByDesc('outstanding')
            ->take(8)
            ->values();

        $trendRows = MonthlyDeduction::query()
            ->selectRaw('month, COUNT(*) as deduction_count, SUM(amount) as amount, SUM(unallocated_amount) as unallocated_amount, SUM(CASE WHEN is_closed = 1 THEN 1 ELSE 0 END) as closed_count')
            ->whereBetween('month', [$trendStart->toDateString(), $monthDate])
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy(fn ($row) => Carbon::parse($row->month)->toDateString());

        $monthlyTrend = collect(range(0, 5))
            ->map(function (int $offset) use ($trendStart, $trendRows) {
                $period = $trendStart->copy()->addMonths($offset);
                $monthKey = $period->toDateString();
                $row = $trendRows->get($monthKey);
                $deductionCount = (int) ($row->deduction_count ?? 0);
                $amount = (float) ($row->amount ?? 0);
                $unallocatedAmount = (float) ($row->unallocated_amount ?? 0);
                $closedCount = (int) ($row->closed_count ?? 0);

                return [
                    'month' => $monthKey,
                    'label' => $period->format('M Y'),
                    'deduction_count' => $deductionCount,
                    'amount' => $amount,
                    'unallocated_amount' => $unallocatedAmount,
                    'allocated_amount' => max(0, $amount - $unallocatedAmount),
                    'closed_count' => $closedCount,
                    'open_count' => max(0, $deductionCount - $closedCount),
                    'allocation_rate' => $amount > 0
                        ? round((($amount - $unallocatedAmount) / $amount) * 100, 1)
                        : 0.0,
                ];
            })
            ->values();

        $priorityBreakdown = Debt::query()
            ->selectRaw('priority, COUNT(*) as debt_count, SUM(balance) as outstanding, SUM(original_amount) as original_amount')
            ->groupBy('priority')
            ->orderBy('priority')
            ->get()
            ->map(function ($row) use ($totalOutstanding) {
                $outstanding = (float) $row->outstanding;

                return [
                    'priority' => (int) $row->priority,
                    'debt_count' => (int) $row->debt_count,
                    'outstanding' => $outstanding,
                    'original_amount' => (float) $row->original_amount,
                    'share_of_outstanding' => $totalOutstanding > 0
                        ? round(($outstanding / $totalOutstanding) * 100, 1)
                        : 0.0,
                ];
            })
            ->values();

        $topPeneroka = Peneroka::query()
            ->join('debts', 'penerokas.id', '=', 'debts.peneroka_id')
            ->join('sites', 'penerokas.site_id', '=', 'sites.id')
            ->selectRaw('penerokas.id as id, penerokas.name as name, sites.name as site_name, sites.code as site_code, COUNT(debts.id) as debt_count, SUM(debts.balance) as outstanding, SUM(debts.original_amount) as original_amount')
            ->groupBy('penerokas.id', 'penerokas.name', 'sites.name', 'sites.code')
            ->orderByDesc('outstanding')
            ->limit(5)
            ->get()
            ->map(function ($row) use ($totalOutstanding) {
                $outstanding = (float) $row->outstanding;

                return [
                    'id' => $row->id,
                    'name' => $row->name,
                    'site_name' => $row->site_name,
                    'site_code' => $row->site_code,
                    'debt_count' => (int) $row->debt_count,
                    'outstanding' => $outstanding,
                    'original_amount' => (float) $row->original_amount,
                    'share_of_outstanding' => $totalOutstanding > 0
                        ? round(($outstanding / $totalOutstanding) * 100, 1)
                        : 0.0,
                ];
            });

        return Inertia::render('Kps/Analytics', [
            'month' => $monthDate,
            'monthLabel' => $month->format('F Y'),
            'overview' => $overview,
            'monthlyTrend' => $monthlyTrend,
            'sitePerformance' => $sitePerformance,
            'priorityBreakdown' => $priorityBreakdown,
            'topPeneroka' => $topPeneroka,
        ]);
    }
}
