<?php

namespace App\Http\Controllers\Kps;

use App\Http\Controllers\Controller;
use App\Http\Requests\Kps\StorePenerokaRequest;
use App\Http\Requests\Kps\UpdatePenerokaRequest;
use App\Models\Kps\Debt;
use App\Models\Kps\Peneroka;
use App\Models\Kps\Site;
use App\Services\Kps\SiteContextResolver;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PenerokaController extends Controller
{
    public function index(Request $request, Site $site, SiteContextResolver $resolver): Response
    {
        $this->authorize('view', $site);
        $this->authorize('viewAny', Peneroka::class);

        $penerokas = Peneroka::query()
            ->where('site_id', $site->id)
            ->withCount('debts')
            ->withSum('debts as total_outstanding', 'balance')
            ->orderBy('name')
            ->paginate(15);

        $summary = [
            'total_peneroka' => Peneroka::query()
                ->where('site_id', $site->id)
                ->count(),
            'with_ic_number' => Peneroka::query()
                ->where('site_id', $site->id)
                ->whereNotNull('ic_number')
                ->where('ic_number', '!=', '')
                ->count(),
            'with_phone' => Peneroka::query()
                ->where('site_id', $site->id)
                ->whereNotNull('phone')
                ->where('phone', '!=', '')
                ->count(),
            'outstanding_total' => (float) Debt::query()
                ->whereHas('peneroka', fn ($query) => $query->where('site_id', $site->id))
                ->sum('balance'),
        ];

        $context = $resolver->resolve($request, $site);

        return Inertia::render('Kps/Peneroka/Index', [
            'site' => $site,
            'penerokas' => $penerokas,
            'summary' => $summary,
            'siteRole' => $context['siteRole'],
        ]);
    }

    public function create(Request $request, Site $site, SiteContextResolver $resolver): Response
    {
        $this->authorize('create', Peneroka::class);

        $context = $resolver->resolve($request, $site);

        return Inertia::render('Kps/Peneroka/Create', [
            'site' => $site,
            'siteRole' => $context['siteRole'],
        ]);
    }

    public function store(StorePenerokaRequest $request, Site $site): RedirectResponse
    {
        $data = $request->validated();
        $data['site_id'] = $site->id;

        Peneroka::create($data);

        return redirect()->route('kps.peneroka.index', $site->id)
            ->with('success', 'Peneroka created successfully.');
    }

    public function edit(Request $request, Site $site, Peneroka $peneroka, SiteContextResolver $resolver): Response
    {
        if ($peneroka->site_id !== $site->id) {
            abort(404);
        }

        $this->authorize('update', $peneroka);

        $context = $resolver->resolve($request, $site);

        return Inertia::render('Kps/Peneroka/Edit', [
            'site' => $site,
            'peneroka' => $peneroka,
            'siteRole' => $context['siteRole'],
        ]);
    }

    public function update(UpdatePenerokaRequest $request, Site $site, Peneroka $peneroka): RedirectResponse
    {
        if ($peneroka->site_id !== $site->id) {
            abort(404);
        }

        $data = $request->validated();
        $data['site_id'] = $site->id;

        $peneroka->update($data);

        return redirect()->route('kps.peneroka.index', $site->id)
            ->with('success', 'Peneroka updated successfully.');
    }

    public function destroy(Site $site, Peneroka $peneroka): RedirectResponse
    {
        if ($peneroka->site_id !== $site->id) {
            abort(404);
        }

        $this->authorize('delete', $peneroka);

        $peneroka->delete();

        return redirect()->route('kps.peneroka.index', $site->id)
            ->with('success', 'Peneroka deleted successfully.');
    }
}
