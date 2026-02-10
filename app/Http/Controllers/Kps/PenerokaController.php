<?php

namespace App\Http\Controllers\Kps;

use App\Http\Controllers\Controller;
use App\Http\Requests\Kps\StorePenerokaRequest;
use App\Http\Requests\Kps\UpdatePenerokaRequest;
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
            ->orderBy('name')
            ->paginate(15);

        $context = $resolver->resolve($request, $site);

        return Inertia::render('Kps/Peneroka/Index', [
            'site' => $site,
            'penerokas' => $penerokas,
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
