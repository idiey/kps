<?php

namespace App\Http\Controllers\Kps;

use App\Http\Controllers\Controller;
use App\Http\Requests\Kps\StoreDebtRequest;
use App\Http\Requests\Kps\UpdateDebtRequest;
use App\Models\Kps\Debt;
use App\Models\Kps\Peneroka;
use App\Models\Kps\Site;
use App\Services\Kps\SiteContextResolver;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DebtController extends Controller
{
    public function index(Request $request, Site $site, SiteContextResolver $resolver): Response
    {
        $this->authorize('view', $site);
        $this->authorize('viewAny', Debt::class);

        $search  = $request->string('search')->trim()->toString();
        $status  = in_array($request->get('status'), ['open', 'paid']) ? $request->get('status') : 'all';
        $sortBy  = in_array($request->get('sort_by'), ['priority', 'balance', 'due_date']) ? $request->get('sort_by') : 'priority';
        $sortDir = $request->get('sort_dir') === 'desc' ? 'desc' : 'asc';

        $debts = Debt::query()
            ->with('peneroka')
            ->whereHas('peneroka', fn ($q) => $q->where('site_id', $site->id))
            ->when($search !== '', fn ($q) => $q->where(fn ($i) =>
                $i->whereHas('peneroka', fn ($p) => $p->where('name', 'like', "%{$search}%"))
                  ->orWhere('description', 'like', "%{$search}%")
            ))
            ->when($status === 'open',  fn ($q) => $q->where('balance', '>', 0))
            ->when($status === 'paid',  fn ($q) => $q->where('balance', '<=', 0))
            ->when($sortBy === 'due_date',
                fn ($q) => $q->orderByRaw('due_date IS NULL')->orderBy('due_date', $sortDir),
                fn ($q) => $q->orderBy($sortBy, $sortDir)
            )
            ->paginate(15)
            ->withQueryString();

        $summary = [
            'total_debts'           => Debt::query()->whereHas('peneroka', fn ($q) => $q->where('site_id', $site->id))->count(),
            'outstanding_total'     => (float) Debt::query()->whereHas('peneroka', fn ($q) => $q->where('site_id', $site->id))->sum('balance'),
            'due_this_month'        => Debt::query()->whereHas('peneroka', fn ($q) => $q->where('site_id', $site->id))->whereBetween('due_date', [Carbon::now()->startOfMonth()->toDateString(), Carbon::now()->endOfMonth()->toDateString()])->count(),
            'highest_priority_open' => Debt::query()->whereHas('peneroka', fn ($q) => $q->where('site_id', $site->id))->where('balance', '>', 0)->min('priority'),
        ];

        $context = $resolver->resolve($request, $site);

        return Inertia::render('Kps/Hutang/Index', [
            'site'     => $site,
            'debts'    => $debts,
            'summary'  => $summary,
            'siteRole' => $context['siteRole'],
            'filters'  => ['search' => $search, 'status' => $status, 'sort_by' => $sortBy, 'sort_dir' => $sortDir],
        ]);
    }

    public function create(Request $request, Site $site, SiteContextResolver $resolver): Response
    {
        $this->authorize('create', Debt::class);

        $penerokas = Peneroka::where('site_id', $site->id)
            ->orderBy('name')
            ->get(['id', 'name']);

        $context = $resolver->resolve($request, $site);

        return Inertia::render('Kps/Hutang/Create', [
            'site' => $site,
            'penerokas' => $penerokas,
            'siteRole' => $context['siteRole'],
        ]);
    }

    public function store(StoreDebtRequest $request, Site $site): RedirectResponse
    {
        $data = $request->validated();

        $peneroka = Peneroka::where('site_id', $site->id)
            ->where('id', $data['peneroka_id'])
            ->firstOrFail();

        $data['original_amount'] = $data['balance'];

        Debt::create($data + ['peneroka_id' => $peneroka->id]);

        return redirect()->route('kps.hutang.index', $site->id)
            ->with('success', 'Hutang created successfully.');
    }

    public function edit(Request $request, Site $site, Debt $debt, SiteContextResolver $resolver): Response
    {
        if ($debt->peneroka?->site_id !== $site->id) {
            abort(404);
        }

        $this->authorize('update', $debt);

        $context = $resolver->resolve($request, $site);

        return Inertia::render('Kps/Hutang/Edit', [
            'site' => $site,
            'debt' => $debt->load('peneroka'),
            'siteRole' => $context['siteRole'],
        ]);
    }

    public function update(UpdateDebtRequest $request, Site $site, Debt $debt): RedirectResponse
    {
        if ($debt->peneroka?->site_id !== $site->id) {
            abort(404);
        }

        $this->authorize('update', $debt);

        $debt->update($request->validated());

        return redirect()->route('kps.hutang.index', $site->id)
            ->with('success', 'Hutang updated successfully.');
    }

    public function destroy(Site $site, Debt $debt): RedirectResponse
    {
        if ($debt->peneroka?->site_id !== $site->id) {
            abort(404);
        }

        $this->authorize('delete', $debt);

        $debt->delete();

        return redirect()->route('kps.hutang.index', $site->id)
            ->with('success', 'Hutang deleted successfully.');
    }
}
