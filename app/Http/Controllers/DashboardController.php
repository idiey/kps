<?php

namespace App\Http\Controllers;

use App\Enums\JobStatus;
use App\Models\User;
use App\Models\WorkshopJob;
use App\Services\JobService;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Controller for dashboard views and statistics.
 */
class DashboardController extends Controller
{
    public function __construct(
        protected JobService $jobService
    ) {}

    /**
     * Display workload dashboard for administrators.
     */
    public function workload(): Response
    {
        // Get all technicians with their job counts
        $technicians = User::where('role', 'juruteknik')
            ->withCount(['assignedJobs as active_jobs_count' => function ($query) {
                $query->whereNotIn('status', [JobStatus::COMPLETED->value, JobStatus::INVOICED->value]);
            }])
            ->with(['assignedJobs' => function ($query) {
                $query->whereNotIn('status', [JobStatus::COMPLETED->value, JobStatus::INVOICED->value])
                    ->with('customer')
                    ->orderBy('due_date', 'asc')
                    ->limit(5);
            }])
            ->orderBy('name')
            ->get();

        // Get overall statistics
        $statistics = [
            'total_jobs' => WorkshopJob::count(),
            'new_jobs' => WorkshopJob::where('status', JobStatus::NEW)->count(),
            'in_progress_jobs' => WorkshopJob::where('status', JobStatus::IN_PROGRESS)->count(),
            'completed_jobs' => WorkshopJob::where('status', JobStatus::COMPLETED)->count(),
            'overdue_jobs' => WorkshopJob::overdue()->count(),
            'unassigned_jobs' => WorkshopJob::whereNull('assigned_to')
                ->whereNotIn('status', [JobStatus::COMPLETED->value, JobStatus::INVOICED->value])
                ->count(),
        ];

        return Inertia::render('Dashboard/Workload', [
            'technicians' => $technicians,
            'statistics' => $statistics,
        ]);
    }

    /**
     * Display technician's personal job dashboard.
     */
    public function myJobs(): Response
    {
        $user = auth()->user();

        $jobs = $this->jobService->getJobsForTechnician($user->id);

        $statistics = [
            'total' => $jobs->count(),
            'new' => $jobs->where('status', JobStatus::NEW)->count(),
            'in_progress' => $jobs->where('status', JobStatus::IN_PROGRESS)->count(),
            'overdue' => $jobs->filter(fn($job) => $job->isOverdue())->count(),
        ];

        return Inertia::render('Dashboard/MyJobs', [
            'jobs' => $jobs,
            'statistics' => $statistics,
        ]);
    }

    /**
     * Get real-time statistics for dashboard widgets.
     */
    public function statistics()
    {
        $stats = $this->jobService->getStatistics();

        // Add additional real-time stats
        $stats['recent_jobs'] = WorkshopJob::with(['customer', 'assignedUser'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $stats['priority_distribution'] = WorkshopJob::select('priority', DB::raw('count(*) as count'))
            ->groupBy('priority')
            ->pluck('count', 'priority');

        return response()->json($stats);
    }
}
