<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Workshop;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class WorkshopAnalyticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display analytics for a specific workshop.
     */
    public function show(Request $request, Workshop $workshop): Response
    {
        Gate::authorize('viewAnalytics', $workshop);

        $period = $request->input('period', 'month');
        $startDate = $this->getStartDate($period);

        $workshop->load(['company']);

        // Job statistics
        $jobStats = [
            'total' => $workshop->jobs()->count(),
            'completed' => $workshop->jobs()->where('status', 'completed')->count(),
            'in_progress' => $workshop->jobs()->whereIn('status', ['pending', 'in_progress', 'assigned'])->count(),
            'period_count' => $workshop->jobs()->where('created_at', '>=', $startDate)->count(),
        ];

        // Customer statistics
        $customerStats = [
            'total' => $workshop->customers()->count(),
            'new_period' => $workshop->customers()->where('created_at', '>=', $startDate)->count(),
        ];

        // User statistics
        $userStats = [
            'total_assigned' => $workshop->assignedUsers()->count(),
            'by_role' => [
                'supervisor' => $workshop->usersByRole('supervisor')->count(),
                'technician' => $workshop->usersByRole('technician')->count(),
                'staff' => $workshop->usersByRole('staff')->count(),
            ],
        ];

        // Jobs by status distribution
        $jobsByStatus = $workshop->jobs()
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Jobs trend (last 7 days or weeks depending on period)
        $jobsTrend = $this->getJobsTrend($workshop, $period, $startDate);

        // Get the current user's role at this site
        $user = $request->user();
        $siteRole = $workshop->getUserRole($user->id);

        // Global admins effectively have site_admin privileges
        if (!$siteRole && $user->hasRole('pentadbiran')) {
            $siteRole = 'site_admin';
        }

        return Inertia::render('Admin/Workshops/Analytics', [
            'workshop' => $workshop,
            'period' => $period,
            'stats' => [
                'jobs' => $jobStats,
                'customers' => $customerStats,
                'users' => $userStats,
                'jobsByStatus' => $jobsByStatus,
                'jobsTrend' => $jobsTrend,
            ],
            // Site context for dual sidebar
            'site' => $workshop,
            'siteRole' => $siteRole,
        ]);
    }

    /**
     * Get the start date based on period.
     */
    private function getStartDate(string $period): Carbon
    {
        return match ($period) {
            'week' => Carbon::now()->subWeek(),
            'month' => Carbon::now()->subMonth(),
            'quarter' => Carbon::now()->subQuarter(),
            'year' => Carbon::now()->subYear(),
            default => Carbon::now()->subMonth(),
        };
    }

    /**
     * Get jobs trend data for charts.
     */
    private function getJobsTrend(Workshop $workshop, string $period, Carbon $startDate): array
    {
        $format = $period === 'week' ? 'Y-m-d' : 'Y-W';
        
        // Fetch jobs and group in PHP for database compatibility (SQLite doesn't have DATE_FORMAT)
        $jobs = $workshop->jobs()
            ->where('created_at', '>=', $startDate)
            ->select('created_at')
            ->get();
        
        $trend = $jobs->groupBy(function ($job) use ($format) {
            return $job->created_at->format($format);
        })->map->count()->sortKeys()->toArray();

        return $trend;
    }
}
