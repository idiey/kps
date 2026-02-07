<?php

namespace App\Http\Controllers;

use App\Enums\PhotoStage;
use App\Http\Requests\Inspection\ApproveInspectionRequest;
use App\Http\Requests\Inspection\StoreInspectionRequest;
use App\Http\Requests\Inspection\UpdateInspectionRequest;
use App\Models\InspectionReport;
use App\Models\User;
use App\Models\WorkshopJob;
use App\Services\InspectionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class InspectionController extends Controller
{
    public function __construct(
        protected InspectionService $inspectionService
    ) {}

    /**
     * Display a listing of inspections.
     */
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', InspectionReport::class);

        $query = InspectionReport::with(['workshopJob.kewPA10', 'workshopJob.asset', 'inspector']);

        // Apply filters
        if ($status = $request->get('status')) {
            $query->where('approval_status', $status);
        }

        if ($inspectorId = $request->get('inspector_id')) {
            $query->where('inspector_id', $inspectorId);
        }

        if ($jobId = $request->get('job_id')) {
            $query->where('workshop_job_id', $jobId);
        }

        if ($search = $request->get('search')) {
            $query->whereHas('workshopJob', function ($q) use ($search) {
                $q->where('job_reference', 'like', "%{$search}%");
            });
        }

        $inspections = $query->orderBy('created_at', 'desc')->paginate(15);

        return Inertia::render('Inspections/Index', [
            'inspections' => $inspections,
            'filters' => $request->only(['status', 'inspector_id', 'job_id', 'search']),
            'inspectors' => User::role('pemeriksa')->orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
     * Show the form for assigning an inspection.
     */
    public function create(WorkshopJob $job): Response
    {
        Gate::authorize('create', InspectionReport::class);

        return Inertia::render('Inspections/Create', [
            'job' => $job->load(['kewPA10', 'asset', 'governmentDepartment']),
            'inspectors' => User::role('pemeriksa')->orderBy('name')->get(['id', 'name', 'email']),
        ]);
    }

    /**
     * Store a newly assigned inspection.
     */
    public function store(StoreInspectionRequest $request, WorkshopJob $job): RedirectResponse
    {
        $inspection = $this->inspectionService->assignInspection(
            $job,
            $request->input('inspector_id'),
            $request->input('notes')
        );

        return redirect()->route('inspections.show', $inspection)
            ->with('success', __('Inspection assigned successfully'));
    }

    /**
     * Display the specified inspection.
     */
    public function show(InspectionReport $inspection): Response
    {
        Gate::authorize('view', $inspection);

        $inspection->load([
            'workshopJob.kewPA10',
            'workshopJob.asset',
            'workshopJob.governmentDepartment',
            'inspector',
            'photos.user',
        ]);

        return Inertia::render('Inspections/Show', [
            'inspection' => $inspection,
            'photoStages' => PhotoStage::options(),
            'canApprove' => auth()->user()->can('approve', $inspection) && $inspection->approval_status === 'pending',
            'canReject' => auth()->user()->can('reject', $inspection) && $inspection->approval_status === 'pending',
        ]);
    }

    /**
     * Show the form for editing the inspection report.
     */
    public function edit(InspectionReport $inspection): Response
    {
        Gate::authorize('update', $inspection);

        $inspection->load(['workshopJob', 'inspector']);

        return Inertia::render('Inspections/Edit', [
            'inspection' => $inspection,
            'photoStages' => PhotoStage::options(),
        ]);
    }

    /**
     * Update the inspection report.
     */
    public function update(UpdateInspectionRequest $request, InspectionReport $inspection): RedirectResponse
    {
        $this->inspectionService->updateReport($inspection, $request->validated());

        return redirect()->route('inspections.show', $inspection)
            ->with('success', __('Inspection report updated successfully'));
    }

    /**
     * Approve the inspection.
     */
    public function approve(ApproveInspectionRequest $request, InspectionReport $inspection): RedirectResponse
    {
        try {
            // Update digital signature first
            $inspection->update([
                'digital_signature' => $request->input('digital_signature'),
            ]);

            $this->inspectionService->approve($inspection, $request->input('notes'));

            return redirect()->route('inspections.show', $inspection)
                ->with('success', __('Inspection approved successfully'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Approve inspection with additional conditions.
     */
    public function approveWithConditions(Request $request, InspectionReport $inspection): RedirectResponse
    {
        Gate::authorize('approve', $inspection);

        $request->validate([
            'conditions' => ['required', 'string'],
            'digital_signature' => ['required', 'string'],
        ]);

        try {
            // Update digital signature first
            $inspection->update([
                'digital_signature' => $request->input('digital_signature'),
            ]);

            $this->inspectionService->approveWithConditions($inspection, $request->input('conditions'));

            return redirect()->route('inspections.show', $inspection)
                ->with('success', __('Inspection approved with conditions'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Reject the inspection.
     */
    public function reject(Request $request, InspectionReport $inspection): RedirectResponse
    {
        Gate::authorize('reject', $inspection);

        $request->validate([
            'reason' => ['required', 'string'],
            'digital_signature' => ['required', 'string'],
        ]);

        // Update digital signature first
        $inspection->update([
            'digital_signature' => $request->input('digital_signature'),
        ]);

        $this->inspectionService->reject($inspection, $request->input('reason'));

        return redirect()->route('inspections.show', $inspection)
            ->with('success', __('Inspection rejected'));
    }
}
