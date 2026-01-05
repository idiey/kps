<?php

namespace App\Http\Controllers;

use App\Enums\JobPriority;
use App\Http\Requests\KewPA10\StoreKewPA10Request;
use App\Http\Requests\KewPA10\UpdateKewPA10Request;
use App\Models\Asset;
use App\Models\GovernmentDepartment;
use App\Models\KewPA10;
use App\Models\WorkshopJob;
use App\Services\KewPA10Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class KewPA10Controller extends Controller
{
    public function __construct(
        protected KewPA10Service $kewPA10Service
    ) {}

    /**
     * Display a listing of KEW.PA-10 forms.
     */
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', KewPA10::class);

        $query = KewPA10::with(['governmentDepartment', 'asset', 'workshopJob']);

        // Apply filters
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('kew_pa_10_number', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($departmentId = $request->get('government_department_id')) {
            $query->where('government_department_id', $departmentId);
        }

        if ($priority = $request->get('priority')) {
            $query->where('priority', $priority);
        }

        if ($request->has('verified')) {
            $verified = filter_var($request->get('verified'), FILTER_VALIDATE_BOOLEAN);
            $query->where('form_completeness_verified', $verified)
                  ->where('signatures_verified', $verified);
        }

        if ($dateFrom = $request->get('date_from')) {
            $query->whereDate('received_date', '>=', $dateFrom);
        }

        if ($dateTo = $request->get('date_to')) {
            $query->whereDate('received_date', '<=', $dateTo);
        }

        $kewPA10s = $query->orderBy('received_date', 'desc')->paginate(15);

        return Inertia::render('KewPA10/Index', [
            'kewPA10s' => $kewPA10s,
            'filters' => $request->only(['search', 'government_department_id', 'priority', 'verified', 'date_from', 'date_to']),
            'governmentDepartments' => GovernmentDepartment::orderBy('name')->get(['id', 'name']),
            'priorities' => JobPriority::options(),
        ]);
    }

    /**
     * Show the form for creating a new KEW.PA-10.
     */
    public function create(): Response
    {
        Gate::authorize('create', KewPA10::class);

        return Inertia::render('KewPA10/Create', [
            'governmentDepartments' => GovernmentDepartment::active()->orderBy('name')->get(['id', 'name', 'department_code']),
            'assets' => Asset::orderBy('asset_name')->get(['id', 'asset_tag', 'asset_name', 'asset_type']),
            'priorities' => JobPriority::options(),
        ]);
    }

    /**
     * Store a newly created KEW.PA-10 in storage.
     */
    public function store(StoreKewPA10Request $request): RedirectResponse
    {
        $kewPA10 = $this->kewPA10Service->registerKewPA10($request->validated());

        return redirect()->route('kew-pa-10.show', $kewPA10)
            ->with('success', __('KEW.PA-10 form registered successfully'));
    }

    /**
     * Display the specified KEW.PA-10.
     */
    public function show(KewPA10 $kewPA10): Response
    {
        Gate::authorize('view', $kewPA10);

        $kewPA10->load(['governmentDepartment', 'asset', 'workshopJob.statusHistories.user']);

        // Explicitly build the data array to ensure all attributes are included
        $kewPA10Data = [
            'id' => $kewPA10->id,
            'kew_pa_10_number' => $kewPA10->kew_pa_10_number,
            'government_department_id' => $kewPA10->government_department_id,
            'asset_id' => $kewPA10->asset_id,
            'description' => $kewPA10->description,
            'priority' => $kewPA10->priority?->value ?? $kewPA10->priority,
            'budget_allocation_reference' => $kewPA10->budget_allocation_reference,
            'kew_pa_10_document_path' => $kewPA10->kew_pa_10_document_path,
            'form_completeness_verified' => $kewPA10->form_completeness_verified,
            'signatures_verified' => $kewPA10->signatures_verified,
            'verification_notes' => $kewPA10->verification_notes,
            'received_date' => $kewPA10->received_date?->format('Y-m-d'),
            'received_by' => $kewPA10->received_by,
            'created_at' => $kewPA10->created_at?->toISOString(),
            'updated_at' => $kewPA10->updated_at?->toISOString(),
            'government_department' => $kewPA10->governmentDepartment,
            'asset' => $kewPA10->asset,
            'workshop_job' => $kewPA10->workshopJob,
        ];

        return Inertia::render('KewPA10/Show', [
            'kewPA10' => $kewPA10Data,
            'canVerify' => !$kewPA10->isVerified() && auth()->user()->can('verify', $kewPA10),
            'canCreateJob' => !empty($kewPA10->kew_pa_10_number)
                && !empty($kewPA10->description)
                && !$kewPA10->workshopJob
                && auth()->user()->can('create', WorkshopJob::class),
        ]);
    }

    /**
     * Show the form for editing the specified KEW.PA-10.
     */
    public function edit(KewPA10 $kewPA10): Response
    {
        Gate::authorize('update', $kewPA10);

        $kewPA10->load(['governmentDepartment', 'asset']);

        return Inertia::render('KewPA10/Edit', [
            'kewPA10' => $kewPA10,
            'governmentDepartments' => GovernmentDepartment::active()->orderBy('name')->get(['id', 'name', 'department_code']),
            'assets' => Asset::orderBy('asset_name')->get(['id', 'asset_tag', 'asset_name', 'asset_type']),
            'priorities' => JobPriority::options(),
        ]);
    }

    /**
     * Update the specified KEW.PA-10 in storage.
     */
    public function update(UpdateKewPA10Request $request, KewPA10 $kewPA10): RedirectResponse
    {
        $kewPA10->update($request->validated());

        return redirect()->route('kew-pa-10.show', $kewPA10)
            ->with('success', __('KEW.PA-10 form updated successfully'));
    }

    /**
     * Remove the specified KEW.PA-10 from storage.
     */
    public function destroy(KewPA10 $kewPA10): RedirectResponse
    {
        Gate::authorize('delete', $kewPA10);

        // Prevent deletion if job exists
        if ($kewPA10->workshopJob) {
            return redirect()->back()
                ->with('error', __('Cannot delete KEW.PA-10 with an existing job'));
        }

        $kewPA10->delete();

        return redirect()->route('kew-pa-10.index')
            ->with('success', __('KEW.PA-10 form deleted successfully'));
    }

    /**
     * Verify KEW.PA-10 form completeness and signatures.
     */
    public function verify(Request $request, KewPA10 $kewPA10): RedirectResponse
    {
        Gate::authorize('verify', $kewPA10);

        $request->validate([
            'form_completeness_verified' => ['required', 'boolean'],
            'signatures_verified' => ['required', 'boolean'],
            'verification_notes' => ['nullable', 'string'],
        ]);

        $this->kewPA10Service->verifyForm($kewPA10, [
            'form_completeness_verified' => $request->boolean('form_completeness_verified'),
            'signatures_verified' => $request->boolean('signatures_verified'),
            'verification_notes' => $request->input('verification_notes'),
        ]);

        return redirect()->back()
            ->with('success', __('KEW.PA-10 form verified successfully'));
    }

    /**
     * Create a workshop job from verified KEW.PA-10.
     */
    public function createJob(Request $request, KewPA10 $kewPA10): RedirectResponse
    {
        Gate::authorize('create', WorkshopJob::class);

        $request->validate([
            'customer_id' => ['nullable', 'exists:customers,id'],
            'inspection_required' => ['boolean'],
            'estimated_hours' => ['nullable', 'numeric', 'min:0'],
            'estimated_cost' => ['nullable', 'numeric', 'min:0'],
            'due_date' => ['nullable', 'date', 'after_or_equal:today'],
            'assigned_to' => ['nullable', 'exists:users,id'],
        ]);

        $job = $this->kewPA10Service->createJobFromKewPA10($kewPA10, $request->all());

        return redirect()->route('jobs.show', $job)
            ->with('success', __('Workshop job created successfully'));
    }

    /**
     * Prepare KEW.PA-10 return package.
     */
    public function prepareReturn(WorkshopJob $job): Response
    {
        Gate::authorize('view', $job);

        $returnPackage = $this->kewPA10Service->getReturnPackage($job);

        return Inertia::render('KewPA10/Return', [
            'returnPackage' => $returnPackage,
            'canReturn' => auth()->user()->can('update', $job->kewPA10),
        ]);
    }

    /**
     * Mark KEW.PA-10 as returned to government department.
     */
    public function markReturned(Request $request, WorkshopJob $job): RedirectResponse
    {
        Gate::authorize('update', $job);

        $request->validate([
            'notes' => ['nullable', 'string'],
        ]);

        $this->kewPA10Service->markAsReturned($job, $request->only('notes'));

        return redirect()->route('jobs.show', $job)
            ->with('success', __('KEW.PA-10 form marked as returned successfully'));
    }
}
