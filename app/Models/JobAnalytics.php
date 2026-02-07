<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JobAnalytics extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'total_jobs',
        'completed_jobs',
        'cancelled_jobs',
        'active_jobs',
        'avg_completion_time',
        'total_revenue',
        'unique_customers',
        'technician_utilization',
    ];

    protected $casts = [
        'date' => 'date',
        'avg_completion_time' => 'decimal:2',
        'total_revenue' => 'decimal:2',
        'technician_utilization' => 'decimal:2',
    ];

    public static function getDailyStats(Carbon $startDate, Carbon $endDate): array
    {
        $stats = WorkshopJob::selectRaw('
                DATE(created_at) as date,
                COUNT(*) as total_jobs,
                SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed_jobs,
                SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as cancelled_jobs,
                SUM(CASE WHEN status IN ("assigned", "in_progress", "inspection") THEN 1 ELSE 0 END) as active_jobs
            ')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $completionTimes = JobStatusHistory::selectRaw('
                DATE(workshop_jobs.created_at) as date,
                AVG(DATEDIFF(MAX(job_status_history.created_at), MIN(job_status_history.created_at))) as avg_completion_time
            ')
            ->join('workshop_jobs', 'job_status_history.workshop_job_id', '=', 'workshop_jobs.id')
            ->where('workshop_jobs.status', 'completed')
            ->whereBetween('workshop_jobs.created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        $customerStats = WorkshopJob::selectRaw('
                DATE(workshop_jobs.created_at) as date,
                COUNT(DISTINCT workshop_jobs.customer_id) as unique_customers
            ')
            ->join('customers', 'workshop_jobs.customer_id', '=', 'customers.id')
            ->whereBetween('workshop_jobs.created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        return $stats->map(function ($stat, $date) use ($completionTimes, $customerStats) {
            return [
                'date' => $date,
                'total_jobs' => $stat->total_jobs,
                'completed_jobs' => $stat->completed_jobs,
                'cancelled_jobs' => $stat->cancelled_jobs,
                'active_jobs' => $stat->active_jobs,
                'completion_rate' => $stat->total_jobs > 0 
                    ? round(($stat->completed_jobs / $stat->total_jobs) * 100, 2) 
                    : 0,
                'avg_completion_time' => $completionTimes->get($date)?->avg_completion_time ?? 0,
                'unique_customers' => $customerStats->get($date)?->unique_customers ?? 0,
            ];
        })->values()->toArray();
    }

    public static function getMonthlyStats(Carbon $startDate, Carbon $endDate): array
    {
        return WorkshopJob::selectRaw('
                DATE_FORMAT(created_at, "%Y-%m") as month,
                COUNT(*) as total_jobs,
                SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed_jobs,
                SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as cancelled_jobs,
                COUNT(DISTINCT customer_id) as unique_customers
            ')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function ($stat) {
                return [
                    'month' => $stat->month,
                    'total_jobs' => $stat->total_jobs,
                    'completed_jobs' => $stat->completed_jobs,
                    'cancelled_jobs' => $stat->cancelled_jobs,
                    'unique_customers' => $stat->unique_customers,
                    'completion_rate' => $stat->total_jobs > 0 
                        ? round(($stat->completed_jobs / $stat->total_jobs) * 100, 2) 
                        : 0,
                ];
            })
            ->toArray();
    }

    public static function getTopTechnicians(Carbon $startDate, Carbon $endDate, int $limit = 10): array
    {
        return WorkshopJob::select('users.name as technician_name')
            ->selectRaw('
                COUNT(workshop_jobs.id) as total_jobs,
                SUM(CASE WHEN workshop_jobs.status = "completed" THEN 1 ELSE 0 END) as completed_jobs,
                AVG(DATEDIFF(workshop_jobs.updated_at, workshop_jobs.created_at)) as avg_completion_days,
                COUNT(DISTINCT workshop_jobs.customer_id) as unique_customers
            ')
            ->join('job_assignments', 'workshop_jobs.id', '=', 'job_assignments.workshop_job_id')
            ->join('users', 'job_assignments.assigned_to', '=', 'users.id')
            ->whereBetween('workshop_jobs.created_at', [$startDate, $endDate])
            ->where('job_assignments.is_current', true)
            ->groupBy('users.id', 'users.name')
            ->orderBy('completed_jobs', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($technician) {
                return [
                    'technician_name' => $technician->technician_name,
                    'total_jobs' => $technician->total_jobs,
                    'completed_jobs' => $technician->completed_jobs,
                    'completion_rate' => $technician->total_jobs > 0 
                        ? round(($technician->completed_jobs / $technician->total_jobs) * 100, 2) 
                        : 0,
                    'avg_completion_days' => round($technician->avg_completion_days, 1),
                    'unique_customers' => $technician->unique_customers,
                ];
            })
            ->toArray();
    }

    public static function getCustomerInsights(Carbon $startDate, Carbon $endDate): array
    {
        $topCustomers = WorkshopJob::select('customers.name', 'customers.customer_type')
            ->selectRaw('
                COUNT(workshop_jobs.id) as job_count,
                SUM(CASE WHEN workshop_jobs.status = "completed" THEN 1 ELSE 0 END) as completed_jobs,
                AVG(DATEDIFF(workshop_jobs.updated_at, workshop_jobs.created_at)) as avg_completion_days
            ')
            ->join('customers', 'workshop_jobs.customer_id', '=', 'customers.id')
            ->whereBetween('workshop_jobs.created_at', [$startDate, $endDate])
            ->groupBy('customers.id', 'customers.name', 'customers.customer_type')
            ->orderBy('job_count', 'desc')
            ->limit(10)
            ->get();

        $customerTypes = Customer::select('customer_type', DB::raw('COUNT(*) as count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('customer_type')
            ->get();

        return [
            'top_customers' => $topCustomers->toArray(),
            'customer_types' => $customerTypes->toArray(),
            'repeat_customer_rate' => self::getRepeatCustomerRate($startDate, $endDate),
        ];
    }

    private static function getRepeatCustomerRate(Carbon $startDate, Carbon $endDate): float
    {
        $totalCustomers = Customer::whereHas('jobs', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        })->count();

        $repeatCustomers = Customer::whereHas('jobs', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        })
        ->whereHas('jobs', function ($query) {
            $query->havingRaw('COUNT(*) > 1');
        })
        ->count();

        return $totalCustomers > 0 ? round(($repeatCustomers / $totalCustomers) * 100, 2) : 0;
    }

    public static function getWorkflowEfficiency(Carbon $startDate, Carbon $EndDate): array
    {
        $workflowStats = WorkshopJob::select('job_templates.name as template_name')
            ->selectRaw('
                COUNT(workshop_jobs.id) as total_jobs,
                SUM(CASE WHEN workshop_jobs.status = "completed" THEN 1 ELSE 0 END) as completed_jobs,
                AVG(DATEDIFF(workshop_jobs.updated_at, workshop_jobs.created_at)) as avg_completion_days
            ')
            ->leftJoin('job_templates', 'workshop_jobs.template_id', '=', 'job_templates.id')
            ->whereBetween('workshop_jobs.created_at', [$startDate, $EndDate])
            ->groupBy('job_templates.id', 'job_templates.name')
            ->orderBy('completed_jobs', 'desc')
            ->get()
            ->map(function ($workflow) {
                return [
                    'template_name' => $workflow->template_name ?? 'No Template',
                    'total_jobs' => $workflow->total_jobs,
                    'completed_jobs' => $workflow->completed_jobs,
                    'completion_rate' => $workflow->total_jobs > 0 
                        ? round(($workflow->completed_jobs / $workflow->total_jobs) * 100, 2) 
                        : 0,
                    'avg_completion_days' => round($workflow->avg_completion_days, 1),
                ];
            })
            ->toArray();

        return [
            'workflow_performance' => $workflowStats,
            'overall_efficiency' => self::calculateOverallEfficiency($startDate, $EndDate),
        ];
    }

    private static function calculateOverallEfficiency(Carbon $startDate, Carbon $endDate): array
    {
        $totalJobs = WorkshopJob::whereBetween('created_at', [$startDate, $endDate])->count();
        $completedJobs = WorkshopJob::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])->count();
        
        $avgCompletionTime = JobStatusHistory::selectRaw('
                AVG(DATEDIFF(MAX(created_at), MIN(created_at))) as avg_days
            ')
            ->whereHas('workshopJob', function ($query) use ($startDate, $endDate) {
                $query->where('status', 'completed')
                    ->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->first()
            ->avg_days ?? 0;

        return [
            'total_jobs' => $totalJobs,
            'completion_rate' => $totalJobs > 0 ? round(($completedJobs / $totalJobs) * 100, 2) : 0,
            'avg_completion_days' => round($avgCompletionTime, 1),
        ];
    }
}