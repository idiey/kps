<?php

namespace App\Http\Controllers\Kps;

use App\Http\Controllers\Controller;
use App\Models\Kps\Debt;
use App\Models\Kps\MonthlyDeduction;
use App\Models\Kps\Peneroka;
use App\Models\Kps\Site;
use App\Services\Kps\SiteExperienceService;
use App\Services\Kps\SiteContextResolver;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(
        Request $request,
        Site $site,
        SiteContextResolver $resolver,
        SiteExperienceService $siteExperience
    ): InertiaResponse
    {
        $this->authorizeReportAccess($request, $site);
        $context = $resolver->resolve($request, $site);

        $search  = $request->string('search')->trim()->toString();
        $sortBy  = $request->get('sort_by', 'name');
        $sortDir = $request->get('sort_dir') === 'desc' ? 'desc' : 'asc';

        $month     = $siteExperience->currentMonth();
        $monthDate = $month->toDateString();

        $penerokas = $siteExperience->siteReportsPaginated($site, $monthDate, $search, $sortBy, $sortDir);

        $payload = $siteExperience->siteReportsPayload($site);

        $summary = [
            'peneroka_count'           => \App\Models\Kps\Peneroka::query()->where('site_id', $site->id)->count(),
            'outstanding_total'        => (float) \App\Models\Kps\Debt::query()->whereHas('peneroka', fn (\Illuminate\Database\Eloquent\Builder $q) => $q->where('site_id', $site->id))->sum('balance'),
            'current_month_deductions' => (float) \App\Models\Kps\MonthlyDeduction::query()->where('site_id', $site->id)->whereDate('month', $monthDate)->sum('amount'),
        ];

        return Inertia::render('Kps/Reports/Index', [
            'site'           => $site,
            'siteRole'       => $context['siteRole'],
            'penerokas'      => $penerokas,
            'summary'        => $summary,
            'priorityMix'    => $payload['priorityMix'] ?? [],
            'recentActivity' => $payload['recentActivity'] ?? [],
            'currentMonth'   => $monthDate,
            'monthLabel'     => $month->format('F Y'),
            'filters'        => ['search' => $search, 'sort_by' => $sortBy, 'sort_dir' => $sortDir],
        ]);
    }

    public function exportSiteCsv(
        Request $request,
        Site $site,
        SiteExperienceService $siteExperience
    ): StreamedResponse
    {
        $this->authorizeReportAccess($request, $site);

        $currentMonth = $siteExperience->currentMonth()->toDateString();
        $penerokas = $siteExperience->siteReportQuery($site, $currentMonth)
            ->get(['id', 'site_id', 'name', 'ic_number', 'phone']);

        $filename = sprintf(
            '%s-site-report-%s.csv',
            Str::slug($site->code ?: $site->name),
            now()->format('Ymd-His')
        );

        return response()->streamDownload(function () use ($site, $currentMonth, $penerokas) {
            $handle = fopen('php://output', 'wb');
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['KPS Site Report']);
            fputcsv($handle, ['Site', $site->name]);
            fputcsv($handle, ['Code', $site->code]);
            fputcsv($handle, ['Month', $currentMonth]);
            fputcsv($handle, []);
            fputcsv($handle, [
                'Name',
                'IC Number',
                'Phone',
                'Debt Count',
                'Total Outstanding',
                'Current Month Deduction',
                'Latest Deduction Month',
            ]);

            foreach ($penerokas as $peneroka) {
                fputcsv($handle, [
                    $peneroka->name,
                    $peneroka->ic_number ?: '',
                    $peneroka->phone ?: '',
                    $peneroka->debts_count ?? 0,
                    number_format((float) ($peneroka->total_outstanding ?? 0), 2, '.', ''),
                    number_format((float) ($peneroka->current_month_deduction_total ?? 0), 2, '.', ''),
                    $peneroka->latest_deduction_month ?: '',
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function penerokaStatement(Request $request, Site $site, Peneroka $peneroka, SiteContextResolver $resolver): InertiaResponse
    {
        $this->authorizeReportAccess($request, $site);
        ['peneroka' => $peneroka, 'deductions' => $deductions, 'summary' => $summary] = $this->statementData($site, $peneroka);

        $context = $resolver->resolve($request, $site);

        return Inertia::render('Kps/Reports/Statement', [
            'site' => $site,
            'peneroka' => $peneroka,
            'deductions' => $deductions,
            'summary' => $summary,
            'siteRole' => $context['siteRole'],
        ]);
    }

    public function exportPenerokaStatementCsv(Request $request, Site $site, Peneroka $peneroka): StreamedResponse
    {
        $this->authorizeReportAccess($request, $site);
        ['peneroka' => $peneroka, 'deductions' => $deductions, 'summary' => $summary] = $this->statementData($site, $peneroka);

        $filename = sprintf(
            '%s-statement.csv',
            Str::slug($site->code.'-'.$peneroka->name)
        );

        return response()->streamDownload(function () use ($site, $peneroka, $deductions, $summary) {
            $handle = fopen('php://output', 'wb');
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['KPS Peneroka Statement']);
            fputcsv($handle, ['Site', $site->name]);
            fputcsv($handle, ['Peneroka', $peneroka->name]);
            fputcsv($handle, ['IC Number', $peneroka->ic_number ?: '']);
            fputcsv($handle, ['Phone', $peneroka->phone ?: '']);
            fputcsv($handle, ['Address', $peneroka->address ?: '']);
            fputcsv($handle, []);
            fputcsv($handle, ['Summary']);
            fputcsv($handle, ['Debt Count', $summary['debt_count']]);
            fputcsv($handle, ['Outstanding Balance', number_format((float) $summary['outstanding_balance'], 2, '.', '')]);
            fputcsv($handle, ['Total Deductions', number_format((float) $summary['deduction_total'], 2, '.', '')]);
            fputcsv($handle, ['Allocated Total', number_format((float) $summary['allocated_total'], 2, '.', '')]);
            fputcsv($handle, ['Unallocated Total', number_format((float) $summary['unallocated_total'], 2, '.', '')]);
            fputcsv($handle, []);
            fputcsv($handle, ['Debts']);
            fputcsv($handle, ['Description', 'Priority', 'Original Amount', 'Balance', 'Due Date']);

            foreach ($peneroka->debts as $debt) {
                fputcsv($handle, [
                    $debt->description ?: $debt->id,
                    $debt->priority,
                    number_format((float) $debt->original_amount, 2, '.', ''),
                    number_format((float) $debt->balance, 2, '.', ''),
                    $debt->due_date?->format('Y-m-d') ?: '',
                ]);
            }

            fputcsv($handle, []);
            fputcsv($handle, ['Monthly Deductions']);
            fputcsv($handle, ['Month', 'Amount', 'Allocated', 'Unallocated', 'Status', 'Allocations']);

            foreach ($deductions as $deduction) {
                $allocationSummary = $deduction->allocations
                    ->map(fn ($allocation) => sprintf(
                        '%s: %s',
                        $allocation->debt?->description ?: $allocation->debt_id,
                        number_format((float) $allocation->amount, 2, '.', '')
                    ))
                    ->implode('; ');

                fputcsv($handle, [
                    $deduction->month?->format('Y-m-d') ?: (string) $deduction->month,
                    number_format((float) $deduction->amount, 2, '.', ''),
                    number_format((float) $deduction->allocations->sum('amount'), 2, '.', ''),
                    number_format((float) $deduction->unallocated_amount, 2, '.', ''),
                    $deduction->is_closed ? 'Closed' : 'Open',
                    $allocationSummary,
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function exportPenerokaStatementPdf(Request $request, Site $site, Peneroka $peneroka): HttpResponse
    {
        $this->authorizeReportAccess($request, $site);
        ['peneroka' => $peneroka, 'deductions' => $deductions, 'summary' => $summary] = $this->statementData($site, $peneroka);

        return Pdf::loadView('reports.kps.peneroka-statement', [
            'site' => $site,
            'peneroka' => $peneroka,
            'deductions' => $deductions,
            'summary' => $summary,
            'generatedAt' => now(),
        ])
            ->setPaper('a4')
            ->download(sprintf('%s-statement.pdf', Str::slug($site->code.'-'.$peneroka->name)));
    }

    private function authorizeReportAccess(Request $request, Site $site): void
    {
        $this->authorize('view', $site);

        if (! $request->user()->hasPermissionTo('kps.view_reports')) {
            abort(403);
        }
    }

    private function statementData(Site $site, Peneroka $peneroka): array
    {
        if ($peneroka->site_id !== $site->id) {
            abort(404);
        }

        $this->authorize('view', $peneroka);

        $peneroka->load(['debts' => function ($query) {
            $query->orderBy('priority')
                ->orderByRaw('due_date IS NULL')
                ->orderBy('due_date')
                ->orderBy('created_at');
        }]);

        $deductions = MonthlyDeduction::query()
            ->with(['allocations.debt'])
            ->where('peneroka_id', $peneroka->id)
            ->orderByDesc('month')
            ->get();

        $summary = [
            'debt_count' => $peneroka->debts->count(),
            'outstanding_balance' => (float) $peneroka->debts->sum('balance'),
            'deduction_total' => (float) $deductions->sum('amount'),
            'allocated_total' => (float) $deductions->sum(fn (MonthlyDeduction $deduction) => $deduction->allocations->sum('amount')),
            'unallocated_total' => (float) $deductions->sum('unallocated_amount'),
        ];

        return [
            'peneroka' => $peneroka,
            'deductions' => $deductions,
            'summary' => $summary,
        ];
    }
}
