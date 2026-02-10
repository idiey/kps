<?php

namespace App\Http\Controllers\Kps;

use App\Http\Controllers\Controller;
use App\Models\Kps\MonthlyDeduction;
use App\Models\Kps\Peneroka;
use App\Models\Kps\Site;
use App\Services\Kps\SiteContextResolver;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ReportController extends Controller
{
    public function index(Request $request, Site $site, SiteContextResolver $resolver): Response
    {
        $this->authorize('view', $site);
        if (!$request->user()->hasPermissionTo('kps.view_reports')) {
            abort(403);
        }

        $penerokas = Peneroka::where('site_id', $site->id)
            ->orderBy('name')
            ->get(['id', 'name']);

        $context = $resolver->resolve($request, $site);

        return Inertia::render('Kps/Reports/Index', [
            'site' => $site,
            'penerokas' => $penerokas,
            'siteRole' => $context['siteRole'],
        ]);
    }

    public function penerokaStatement(Request $request, Site $site, Peneroka $peneroka, SiteContextResolver $resolver): Response
    {
        if ($peneroka->site_id !== $site->id) {
            abort(404);
        }

        $this->authorize('view', $peneroka);
        if (!$request->user()->hasPermissionTo('kps.view_reports')) {
            abort(403);
        }

        $peneroka->load(['debts' => function ($query) {
            $query->orderBy('priority')->orderByRaw('due_date IS NULL')->orderBy('due_date')->orderBy('created_at');
        }]);

        $deductions = MonthlyDeduction::query()
            ->with(['allocations.debt'])
            ->where('peneroka_id', $peneroka->id)
            ->orderByDesc('month')
            ->get();

        $context = $resolver->resolve($request, $site);

        return Inertia::render('Kps/Reports/Statement', [
            'site' => $site,
            'peneroka' => $peneroka,
            'deductions' => $deductions,
            'siteRole' => $context['siteRole'],
        ]);
    }
}
