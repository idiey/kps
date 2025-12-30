<?php

namespace App\Http\Controllers;

use App\Http\Requests\RepairCompletion\StoreRepairCompletionRequest;
use App\Http\Requests\RepairCompletion\UpdateRepairCompletionRequest;
use App\Models\RepairCompletionReport;
use App\Models\WorkshopJob;
use App\Services\RepairCompletionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class RepairCompletionController extends Controller
{
    public function __construct(
        protected RepairCompletionService $completionService
    ) {}

    /**
     * Show the form for creating a completion report.
     */
    public function create(WorkshopJob $job): Response
    {
        Gate::authorize('create', RepairCompletionReport::class);

        $job->load(['kewPA10', 'asset', 'assignedUser']);

        $photoRequirements = $this->completionService->photoService->validatePhotoRequirements($job);

        return Inertia::render('Completion/Create', [
            'job' => $job,
            'photoRequirements' => $photoRequirements,
            'qualityRatings' => [
                ['value' => 1, 'label' => '1 - Poor'],
                ['value' => 2, 'label' => '2 - Fair'],
                ['value' => 3, 'label' => '3 - Good'],
                ['value' => 4, 'label' => '4 - Very Good'],
                ['value' => 5, 'label' => '5 - Excellent'],
            ],
        ]);
    }

    /**
     * Store a newly created completion report.
     */
    public function store(StoreRepairCompletionRequest $request, WorkshopJob $job): RedirectResponse
    {
        $report = $this->completionService->createCompletionReport(
            $job,
            auth()->id(),
            $request->validated()
        );

        return redirect()->route('completion.show', $report)
            ->with('success', __('Completion report created successfully'));
    }

    /**
     * Display the specified completion report.
     */
    public function show(RepairCompletionReport $report): Response
    {
        Gate::authorize('view', $report);

        $report->load(['workshopJob.kewPA10', 'workshopJob.asset', 'technician']);

        $validationErrors = $this->completionService->validateCompletion($report);
        $canSubmit = empty($validationErrors) && !$report->workshopJob->status->value === 'pending_review';

        return Inertia::render('Completion/Show', [
            'report' => $report,
            'validationErrors' => $validationErrors,
            'canSubmit' => $canSubmit,
            'canSign' => auth()->user()->can('update', $report) && !$report->isSigned(),
            'canEdit' => auth()->user()->can('update', $report) && !$report->isSigned(),
        ]);
    }

    /**
     * Show the form for editing the completion report.
     */
    public function edit(RepairCompletionReport $report): Response
    {
        Gate::authorize('update', $report);

        if ($report->isSigned()) {
            return redirect()->route('completion.show', $report)
                ->with('error', __('Cannot edit a signed completion report'));
        }

        $report->load(['workshopJob', 'technician']);

        $photoRequirements = $this->completionService->photoService->validatePhotoRequirements($report->workshopJob);

        return Inertia::render('Completion/Edit', [
            'report' => $report,
            'photoRequirements' => $photoRequirements,
            'qualityRatings' => [
                ['value' => 1, 'label' => '1 - Poor'],
                ['value' => 2, 'label' => '2 - Fair'],
                ['value' => 3, 'label' => '3 - Good'],
                ['value' => 4, 'label' => '4 - Very Good'],
                ['value' => 5, 'label' => '5 - Excellent'],
            ],
        ]);
    }

    /**
     * Update the specified completion report.
     */
    public function update(UpdateRepairCompletionRequest $request, RepairCompletionReport $report): RedirectResponse
    {
        try {
            $this->completionService->updateCompletionReport($report, $request->validated());

            return redirect()->route('completion.show', $report)
                ->with('success', __('Completion report updated successfully'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Sign the completion report.
     */
    public function sign(Request $request, RepairCompletionReport $report): RedirectResponse
    {
        Gate::authorize('update', $report);

        $request->validate([
            'signature' => ['required', 'string'],
        ]);

        $this->completionService->signReport($report, $request->input('signature'));

        return redirect()->back()
            ->with('success', __('Completion report signed successfully'));
    }

    /**
     * Submit completion report for supervisor review.
     */
    public function submitForReview(RepairCompletionReport $report): RedirectResponse
    {
        Gate::authorize('update', $report);

        try {
            $job = $this->completionService->submitForReview($report);

            return redirect()->route('jobs.show', $job)
                ->with('success', __('Completion report submitted for review'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Add a part to the completion report.
     */
    public function addPart(Request $request, RepairCompletionReport $report): RedirectResponse
    {
        Gate::authorize('update', $report);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:1'],
            'cost' => ['required', 'numeric', 'min:0'],
        ]);

        $this->completionService->addPart(
            $report,
            $request->input('name'),
            $request->input('quantity'),
            $request->input('cost')
        );

        return redirect()->back()
            ->with('success', __('Part added successfully'));
    }

    /**
     * Remove a part from the completion report.
     */
    public function removePart(RepairCompletionReport $report, int $partIndex): RedirectResponse
    {
        Gate::authorize('update', $report);

        $parts = $report->parts_used ?? [];

        if (!isset($parts[$partIndex])) {
            return redirect()->back()
                ->with('error', __('Part not found'));
        }

        array_splice($parts, $partIndex, 1);

        $this->completionService->updateParts($report, $parts);

        return redirect()->back()
            ->with('success', __('Part removed successfully'));
    }
}
