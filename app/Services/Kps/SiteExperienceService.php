<?php

namespace App\Services\Kps;

use App\Models\Kps\AuditLog;
use App\Models\Kps\Debt;
use App\Models\Kps\MonthlyDeduction;
use App\Models\Kps\Peneroka;
use App\Models\Kps\Site;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class SiteExperienceService
{
    public function currentMonth(): Carbon
    {
        return now()->startOfMonth();
    }

    public function siteDashboardPayload(Site $site): array
    {
        $month = $this->currentMonth();
        $monthDate = $month->toDateString();
        $monthlyDeductions = MonthlyDeduction::query()
            ->where('site_id', $site->id)
            ->whereDate('month', $monthDate);

        $totalMonthDeductions = (float) (clone $monthlyDeductions)->sum('amount');
        $totalMonthUnallocated = (float) (clone $monthlyDeductions)->sum('unallocated_amount');

        $topPeneroka = Peneroka::query()
            ->where('site_id', $site->id)
            ->withCount('debts')
            ->withSum('debts as outstanding', 'balance')
            ->orderByDesc('outstanding')
            ->limit(5)
            ->get(['id', 'name', 'ic_number', 'phone'])
            ->map(fn (Peneroka $peneroka) => [
                'id' => $peneroka->id,
                'name' => $peneroka->name,
                'ic_number' => $peneroka->ic_number,
                'phone' => $peneroka->phone,
                'debt_count' => (int) ($peneroka->debts_count ?? 0),
                'outstanding' => (float) ($peneroka->outstanding ?? 0),
            ])
            ->values();

        return [
            'monthLabel' => $month->format('F Y'),
            'stats' => [
                'peneroka_count' => Peneroka::query()->where('site_id', $site->id)->count(),
                'active_debt_count' => Debt::query()
                    ->whereHas('peneroka', fn (Builder $query) => $query->where('site_id', $site->id))
                    ->where('balance', '>', 0)
                    ->count(),
                'outstanding' => (float) Debt::query()
                    ->whereHas('peneroka', fn (Builder $query) => $query->where('site_id', $site->id))
                    ->sum('balance'),
                'current_month_deductions' => $totalMonthDeductions,
                'allocation_rate' => $totalMonthDeductions > 0
                    ? round((($totalMonthDeductions - $totalMonthUnallocated) / $totalMonthDeductions) * 100, 1)
                    : 0.0,
            ],
            'monthlyTrend' => $this->monthlyTrend($site->id),
            'topPeneroka' => $topPeneroka,
            'recentActivity' => $this->auditActivity($site->id, 5),
        ];
    }

    public function siteReportsPayload(Site $site): array
    {
        $month = $this->currentMonth();
        $monthDate = $month->toDateString();
        $penerokas = $this->siteReportQuery($site, $monthDate)
            ->get(['id', 'site_id', 'name', 'ic_number', 'phone']);

        $summary = [
            'peneroka_count' => Peneroka::query()->where('site_id', $site->id)->count(),
            'outstanding_total' => (float) Debt::query()
                ->whereHas('peneroka', fn (Builder $query) => $query->where('site_id', $site->id))
                ->sum('balance'),
            'current_month_deductions' => (float) MonthlyDeduction::query()
                ->where('site_id', $site->id)
                ->whereDate('month', $monthDate)
                ->sum('amount'),
        ];

        $priorityMix = Debt::query()
            ->join('penerokas', 'debts.peneroka_id', '=', 'penerokas.id')
            ->where('penerokas.site_id', $site->id)
            ->selectRaw('debts.priority as priority, COUNT(debts.id) as debt_count, SUM(debts.balance) as outstanding')
            ->groupBy('debts.priority')
            ->orderBy('debts.priority')
            ->get()
            ->map(function ($row) use ($summary) {
                $outstanding = (float) $row->outstanding;

                return [
                    'priority' => (int) $row->priority,
                    'debt_count' => (int) $row->debt_count,
                    'outstanding' => $outstanding,
                    'share_of_outstanding' => $summary['outstanding_total'] > 0
                        ? round(($outstanding / $summary['outstanding_total']) * 100, 1)
                        : 0.0,
                ];
            })
            ->values();

        return [
            'currentMonth' => $monthDate,
            'monthLabel' => $month->format('F Y'),
            'penerokas' => $penerokas,
            'summary' => $summary,
            'priorityMix' => $priorityMix,
            'recentActivity' => $this->auditActivity($site->id, 5),
        ];
    }

    public function siteReportsPaginated(Site $site, string $monthDate, string $search, string $sortBy, string $sortDir, int $perPage = 20): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = $this->siteReportQuery($site, $monthDate);

        if ($search !== '') {
            $query->where(fn (Builder $q) =>
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('ic_number', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
            );
        }

        $allowed = ['name', 'total_outstanding', 'current_month_deduction_total'];
        $query->orderBy(in_array($sortBy, $allowed) ? $sortBy : 'name', $sortDir === 'desc' ? 'desc' : 'asc');

        return $query->paginate($perPage)->withQueryString();
    }

    public function siteReportQuery(Site $site, string $currentMonth): Builder
    {
        return Peneroka::query()
            ->where('site_id', $site->id)
            ->withCount('debts')
            ->withSum('debts as total_outstanding', 'balance')
            ->withSum([
                'monthlyDeductions as current_month_deduction_total' => fn (Builder $query) => $query->whereDate('month', $currentMonth),
            ], 'amount')
            ->withMax('monthlyDeductions as latest_deduction_month', 'month')
            ->orderBy('name');
    }

    public function monthlyTrend(?string $siteId = null, int $months = 6): Collection
    {
        $month = $this->currentMonth();
        $trendStart = $month->copy()->subMonths($months - 1);

        $query = MonthlyDeduction::query()
            ->selectRaw('month, COUNT(*) as deduction_count, SUM(amount) as amount, SUM(unallocated_amount) as unallocated_amount, SUM(CASE WHEN is_closed = 1 THEN 1 ELSE 0 END) as closed_count')
            ->whereBetween('month', [$trendStart->toDateString(), $month->toDateString()])
            ->groupBy('month')
            ->orderBy('month');

        if ($siteId) {
            $query->where('site_id', $siteId);
        }

        $rows = $query->get()->keyBy(fn ($row) => Carbon::parse($row->month)->toDateString());

        return collect(range(0, $months - 1))
            ->map(function (int $offset) use ($trendStart, $rows) {
                $period = $trendStart->copy()->addMonths($offset);
                $monthKey = $period->toDateString();
                $row = $rows->get($monthKey);
                $amount = (float) ($row->amount ?? 0);
                $unallocatedAmount = (float) ($row->unallocated_amount ?? 0);

                return [
                    'month' => $monthKey,
                    'label' => $period->format('M'),
                    'amount' => $amount,
                    'allocated_amount' => max(0, $amount - $unallocatedAmount),
                    'unallocated_amount' => $unallocatedAmount,
                    'closed_count' => (int) ($row->closed_count ?? 0),
                    'deduction_count' => (int) ($row->deduction_count ?? 0),
                    'allocation_rate' => $amount > 0
                        ? round((($amount - $unallocatedAmount) / $amount) * 100, 1)
                        : 0.0,
                ];
            })
            ->values();
    }

    public function auditActivity(?string $siteId = null, int $limit = 6): Collection
    {
        $query = AuditLog::query()
            ->with(['site:id,name,code', 'user:id,name,email'])
            ->latest();

        if ($siteId) {
            $query->where('site_id', $siteId);
        }

        return $query
            ->limit($limit)
            ->get()
            ->map(function (AuditLog $log) {
                $summary = collect($log->metadata ?? [])
                    ->map(function ($value, string $key) {
                        $label = str($key)->replace('_', ' ')->title()->toString();

                        return sprintf('%s %s', $label, $value);
                    })
                    ->implode(' | ');

                return [
                    'id' => $log->id,
                    'action' => $log->action,
                    'action_label' => str($log->action)->replace('_', ' ')->title()->toString(),
                    'created_at' => $log->created_at?->format('Y-m-d H:i:s'),
                    'actor_name' => $log->user?->name ?? 'System',
                    'actor_email' => $log->user?->email,
                    'site_name' => $log->site?->name,
                    'site_code' => $log->site?->code,
                    'summary' => $summary !== '' ? $summary : 'No metadata captured',
                ];
            })
            ->values();
    }
}
