<?php

namespace App\Http\Controllers;

use App\Models\WorkshopJob;
use App\Services\KewPa10ApprovalService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Controller for handling KEW.PA-10 approval workflows.
 *
 * Routes:
 * - GET  /jobs/kew/pending - List pending approvals
 * - POST /jobs/kew/{job}/approve - Approve inspection
 * - POST /jobs/kew/{job}/reject - Reject inspection
 */
class KewApprovalController extends Controller
{
    public function __construct(
        protected KewPa10ApprovalService $approvalService
    ) {
        // Only supervisors and admins can access these routes
        $this->middleware(['auth', 'role:penyelia|pentadbiran|pelulus']);
    }

    /**
     * Display pending KEW.PA-10 approvals.
     */
    public function index(): Response
    {
        $pendingApprovals = $this->approvalService->getPendingApprovals();

        $statistics = $this->approvalService->getApprovalStatistics([
            'date_from' => now()->startOfMonth(),
            'date_to' => now()->endOfMonth(),
        ]);

        return Inertia::render('Jobs/Kew/PendingApprovals', [
            'pendingApprovals' => $pendingApprovals,
            'statistics' => $statistics,
        ]);
    }

    /**
     * Approve a KEW.PA-10 inspection.
     */
    public function approve(WorkshopJob $job): RedirectResponse
    {
        Gate::authorize('approve-kew-inspection', $job);

        try {
            $this->approvalService->approve($job, auth()->user());

            return redirect()
                ->back()
                ->with('success', "Job #{$job->job_number} has been approved successfully.");
        } catch (\InvalidArgumentException $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'An error occurred while approving the job. Please try again.')
                ->withInput();
        }
    }

    /**
     * Reject a KEW.PA-10 inspection.
     */
    public function reject(WorkshopJob $job, Request $request): RedirectResponse
    {
        Gate::authorize('approve-kew-inspection', $job);

        $validated = $request->validate([
            'reason' => 'required|string|min:10|max:1000',
        ], [
            'reason.required' => 'Please provide a reason for rejection.',
            'reason.min' => 'Rejection reason must be at least 10 characters.',
            'reason.max' => 'Rejection reason must not exceed 1000 characters.',
        ]);

        try {
            $this->approvalService->reject(
                $job,
                auth()->user(),
                $validated['reason']
            );

            return redirect()
                ->back()
                ->with('success', "Job #{$job->job_number} has been rejected. The team will address your feedback.");
        } catch (\InvalidArgumentException $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'An error occurred while rejecting the job. Please try again.')
                ->withInput();
        }
    }

    /**
     * Show approval history for a job.
     */
    public function history(WorkshopJob $job): Response
    {
        Gate::authorize('view', $job);

        $history = $job->statusHistories()
            ->whereIn('to_status', ['INSPECTION_APPROVED', 'INSPECTION_REJECTED'])
            ->with('user')
            ->orderBy('changed_at', 'desc')
            ->get();

        return Inertia::render('Jobs/Kew/ApprovalHistory', [
            'job' => $job->load(['customer', 'assignedUser']),
            'history' => $history,
        ]);
    }
}
