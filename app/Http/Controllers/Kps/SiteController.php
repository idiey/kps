<?php

namespace App\Http\Controllers\Kps;

use App\Http\Controllers\Controller;
use App\Http\Requests\Kps\StoreSiteRequest;
use App\Http\Requests\Kps\UpdateSiteRequest;
use App\Models\Kps\Site;
use App\Services\Kps\SiteExperienceService;
use App\Services\Kps\SiteContextResolver;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SiteController extends Controller
{
    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Site::class);

        $sites = Site::query()
            ->orderBy('name')
            ->paginate(15);

        return Inertia::render('Kps/Sites/Index', [
            'sites' => $sites,
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Site::class);

        return Inertia::render('Kps/Sites/Create');
    }

    public function store(StoreSiteRequest $request): RedirectResponse
    {
        Site::create($request->validated());

        return redirect()->route('kps.sites.index')
            ->with('success', 'Site created successfully.');
    }

    public function show(
        Request $request,
        Site $site,
        SiteContextResolver $resolver,
        SiteExperienceService $siteExperience
    ): Response
    {
        $this->authorize('view', $site);
        $context = $resolver->resolve($request, $site);

        return Inertia::render('Kps/Sites/Show', [
            'site' => $site,
            'siteRole' => $context['siteRole'],
            ...$siteExperience->siteDashboardPayload($site),
        ]);
    }

    public function edit(Site $site): Response
    {
        $this->authorize('update', $site);

        return Inertia::render('Kps/Sites/Edit', [
            'site' => $site,
        ]);
    }

    public function update(UpdateSiteRequest $request, Site $site): RedirectResponse
    {
        $site->update($request->validated());

        return redirect()->route('kps.sites.index')
            ->with('success', 'Site updated successfully.');
    }

    public function destroy(Site $site): RedirectResponse
    {
        $this->authorize('delete', $site);

        $site->delete();

        return redirect()->route('kps.sites.index')
            ->with('success', 'Site deleted successfully.');
    }
}
