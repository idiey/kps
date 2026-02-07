<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWorkshopRequest;
use App\Http\Requests\UpdateWorkshopRequest;
use App\Models\Company;
use App\Models\Workshop;
use App\Enums\JobStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class WorkshopController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Only require pentadbiran role for create, edit, update, delete routes
        // View routes (index, show) use policy-based authorization
        $this->middleware('role:pentadbiran')->except(['index', 'show']);
    }

    /**
     * Display a listing of workshops.
     */
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', Workshop::class);

        $user = $request->user();
        
        $query = Workshop::with('company')
            ->withCount(['jobs', 'customers', 'assignedUsers']);

        // HQ-level users only see their company's workshops
        if ($user->company_id) {
            $query->forCompany($user->company_id);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Filter by company (global admins only)
        if ($request->filled('company_id') && !$user->company_id) {
            $query->where('company_id', $request->company_id);
        }

        $workshops = $query->orderBy('name')->paginate(15);

        return Inertia::render('Admin/Workshops/Index', [
            'workshops' => $workshops,
            'companies' => !$user->company_id 
                ? Company::active()->orderBy('name')->get(['id', 'name']) 
                : [],
            'filters' => $request->only(['search', 'status', 'company_id']),
        ]);
    }

    /**
     * Show the form for creating a new workshop.
     */
    public function create(): Response
    {
        Gate::authorize('create', Workshop::class);

        return Inertia::render('Admin/Workshops/Create', [
            'companies' => Company::active()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
     * Store a newly created workshop.
     */
    public function store(StoreWorkshopRequest $request): RedirectResponse
    {
        Workshop::create($request->validated());

        return redirect()->route('admin.workshops.index')
            ->with('success', 'Workshop created successfully.');
    }

    /**
     * Display the specified workshop (site dashboard).
     */
    public function show(Request $request, Workshop $workshop): Response
    {
        Gate::authorize('view', $workshop);

        $workshop->load([
            'company',
            'assignedUsers',
            'jobs' => fn($q) => $q->latest()->limit(10),
            'customers' => fn($q) => $q->latest()->limit(10),
        ]);

        $workshop->loadCount(['jobs', 'customers', 'assignedUsers']);

        // Analytic Data
        $stats = [
            'total_jobs' => $workshop->jobs()->count(),
            'jobs_this_month' => $workshop->jobs()
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'revenue_this_month' => $workshop->jobs()
                ->where('status', JobStatus::INVOICED->value)
                ->whereNotNull('actual_cost')
                ->whereMonth('invoiced_at', now()->month)
                ->whereYear('invoiced_at', now()->year)
                ->sum('actual_cost'),
            'active_technicians' => $workshop->assignedUsers()->wherePivot('role', 'juruteknik')->count(),
            'pending_actions' => $workshop->jobs()->whereIn('status', ['new', 'pending_approval'])->count(),
        ];

        // Job Status Distribution
        $jobDistribution = $workshop->jobs()
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        // Technician Workload (Active jobs per tech)
        $techWorkload = $workshop->assignedUsers()
            ->wherePivot('role', 'juruteknik')
            ->withCount(['assignedJobs as active_jobs_count' => function ($query) {
                $query->whereIn('status', ['in_progress', 'repair_in_progress', 'inspection_in_progress']);
            }])
            ->get()
            ->map(fn($tech) => [
                'name' => $tech->name,
                'jobs' => $tech->active_jobs_count,
            ]);

        // Get the current user's role at this site
        $user = $request->user();
        $siteRole = $workshop->getUserRole($user->id);

        // Global admins effectively have site_admin privileges
        if (!$siteRole && $user->hasRole('pentadbiran')) {
            $siteRole = 'site_admin';
        }

        return Inertia::render('Admin/Workshops/Show', [
            'workshop' => $workshop,
            'stats' => $stats,
            'jobDistribution' => $jobDistribution,
            'techWorkload' => $techWorkload,
            // Site context for dual sidebar
            'site' => $workshop,
            'siteRole' => $siteRole,
        ]);
    }


    /**
     * Show the form for editing the specified workshop.
     */
    public function edit(Workshop $workshop): Response
    {
        Gate::authorize('update', $workshop);

        $workshop->load('company');

        return Inertia::render('Admin/Workshops/Edit', [
            'workshop' => $workshop,
            'companies' => Company::active()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
     * Update the specified workshop.
     */
    public function update(UpdateWorkshopRequest $request, Workshop $workshop): RedirectResponse
    {
        $workshop->update($request->validated());

        return redirect()->route('admin.workshops.index')
            ->with('success', 'Workshop updated successfully.');
    }

    /**
     * Remove the specified workshop.
     */
    public function destroy(Workshop $workshop): RedirectResponse
    {
        Gate::authorize('delete', $workshop);

        // Check if workshop has jobs
        if ($workshop->jobs()->count() > 0) {
            return back()->withErrors([
                'error' => 'Cannot delete workshop with existing jobs. Archive it instead.',
            ]);
        }

        $workshop->delete();

        return redirect()->route('admin.workshops.index')
            ->with('success', 'Workshop deleted successfully.');
    }

    /**
     * Display jobs for the specified workshop (site context).
     */
    public function jobs(Request $request, Workshop $workshop): Response
    {
        Gate::authorize('view', $workshop);

        // Get the current user's role at this site
        $user = $request->user();
        $siteRole = $workshop->getUserRole($user->id);

        // Global admins effectively have site_admin privileges
        if (!$siteRole && $user->hasRole('pentadbiran')) {
            $siteRole = 'site_admin';
        }

        // Fetch jobs for this workshop
        $query = $workshop->jobs()
            ->with(['customer', 'assigned_user'])
            ->latest();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('job_number', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhereHas('customer', fn($cq) => $cq->where('name', 'like', "%{$search}%"));
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $jobs = $query->paginate(15);

        return Inertia::render('Admin/Workshops/Jobs', [
            'workshop' => $workshop,
            'jobs' => $jobs,
            'filters' => $request->only(['search', 'status']),
            // Site context for dual sidebar
            'site' => $workshop,
            'siteRole' => $siteRole,
        ]);
    }

    /**
     * Toggle workshop active status.
     */

    public function toggleStatus(Workshop $workshop): RedirectResponse
    {
        Gate::authorize('update', $workshop);

        $workshop->update([
            'is_active' => !$workshop->is_active,
        ]);

        $status = $workshop->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Workshop {$status} successfully.");
    }
}
