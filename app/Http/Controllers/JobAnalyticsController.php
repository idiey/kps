<?php

namespace App\Http\Controllers;

use App\Models\WorkshopJob;
use App\Models\Customer;
use App\Models\JobStatusHistory;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class JobAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', '30days');
        $startDate = $this->getStartDate($period);
        $endDate = now();

        return Inertia::render('Analytics/Dashboard', [
            'analytics' => [
                'overview' => $this->getOverviewStats($startDate, $endDate),
                'jobTrends' => $this->getJobTrends($startDate, $endDate, $period),
                'statusDistribution' => $this->getStatusDistribution($startDate, $endDate),
                'customerStats' => $this->getCustomerStats($startDate, $endDate),
                'technicianPerformance' => $this->getTechnicianPerformance($startDate, $endDate),
                'completionTimes' => $this->getCompletionTimes($startDate, $endDate),
                'monthlyRevenue' => $this->getMonthlyRevenue($startDate, $endDate),
            ],
            'filters' => [
                'period' => $period,
                'availablePeriods' => [
                    '7days' => 'Last 7 days',
                    '30days' => 'Last 30 days',
                    '90days' => 'Last 90 days',
                    '6months' => 'Last 6 months',
                    '1year' => 'Last year',
                ],
            ],
        ]);
    }

    private function getStartDate(string $period): Carbon
    {
        return match ($period) {
            '7days' => now()->subDays(7),
            '30days' => now()->subDays(30),
            '90days' => now()->subDays(90),
            '6months' => now()->subMonths(6),
            '1year' => now()->subYear(),
            default => now()->subDays(30),
        };
    }

    private function getOverviewStats(Carbon $startDate, Carbon $endDate): array
    {
        $totalJobs = WorkshopJob::whereBetween('created_at', [$startDate, $endDate])->count();
        $completedJobs = WorkshopJob::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])->count();
        $activeJobs = WorkshopJob::whereIn('status', ['assigned', 'in_progress', 'inspection'])
            ->whereBetween('created_at', [$startDate, $endDate])->count();
        $totalCustomers = Customer::whereBetween('created_at', [$startDate, $endDate])->count();

        return [
            'total_jobs' => $totalJobs,
            'completed_jobs' => $completedJobs,
            'active_jobs' => $activeJobs,
            'total_customers' => $totalCustomers,
            'completion_rate' => $totalJobs > 0 ? round(($completedJobs / $totalJobs) * 100, 2) : 0,
        ];
    }

    private function getJobTrends(Carbon $startDate, Carbon $endDate, string $period): array
    {
        $groupBy = match ($period) {
            '7days', '30days' => 'DATE(created_at)',
            '90days' => 'DATE_FORMAT(created_at, "%Y-%u")',
            '6months', '1year' => 'DATE_FORMAT(created_at, "%Y-%m")',
            default => 'DATE(created_at)',
        };

        $jobs = WorkshopJob::select([
            DB::raw("{$groupBy} as period"),
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed'),
        ])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw($groupBy))
            ->orderBy('period')
            ->get();

        return $jobs->map(function ($job) {
            return [
                'period' => $job->period,
                'total' => $job->total,
                'completed' => $job->completed,
                'completion_rate' => $job->total > 0 ? round(($job->completed / $job->total) * 100, 2) : 0,
            ];
        })->toArray();
    }

    private function getStatusDistribution(Carbon $startDate, Carbon $endDate): array
    {
        return WorkshopJob::select('status', DB::raw('COUNT(*) as count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('status')
            ->orderBy('count', 'desc')
            ->get()
            ->map(function ($status) {
                return [
                    'status' => $status->status,
                    'count' => $status->count,
                    'label' => $this->getStatusLabel($status->status),
                ];
            })
            ->toArray();
    }

    private function getCustomerStats(Carbon $startDate, Carbon $endDate): array
    {
        $customerTypes = Customer::select('customer_type', DB::raw('COUNT(*) as count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('customer_type')
            ->get();

        $topCustomers = WorkshopJob::select('customers.name', 'customers.customer_type')
            ->selectRaw('COUNT(workshop_jobs.id) as job_count')
            ->join('customers', 'workshop_jobs.customer_id', '=', 'customers.id')
            ->whereBetween('workshop_jobs.created_at', [$startDate, $endDate])
            ->groupBy('customers.id', 'customers.name', 'customers.customer_type')
            ->orderBy('job_count', 'desc')
            ->limit(10)
            ->get();

        return [
            'types' => $customerTypes->map(function ($type) {
                return [
                    'type' => $type->customer_type,
                    'count' => $type->count,
                    'label' => $this->getCustomerTypeLabel($type->customer_type),
                ];
            })->toArray(),
            'top_customers' => $topCustomers->toArray(),
        ];
    }

    private function getTechnicianPerformance(Carbon $startDate, Carbon $endDate): array
    {
        return WorkshopJob::select('users.name as technician_name')
            ->selectRaw('COUNT(workshop_jobs.id) as total_jobs')
            ->selectRaw('SUM(CASE WHEN workshop_jobs.status = "completed" THEN 1 ELSE 0 END) as completed_jobs')
            ->selectRaw('AVG(DATEDIFF(workshop_jobs.updated_at, workshop_jobs.created_at)) as avg_completion_days')
            ->join('job_assignments', 'workshop_jobs.id', '=', 'job_assignments.workshop_job_id')
            ->join('users', 'job_assignments.assigned_to', '=', 'users.id')
            ->whereBetween('workshop_jobs.created_at', [$startDate, $endDate])
            ->where('job_assignments.is_current', true)
            ->groupBy('users.id', 'users.name')
            ->orderBy('completed_jobs', 'desc')
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
                ];
            })
            ->toArray();
    }

    private function getCompletionTimes(Carbon $startDate, Carbon $endDate): array
    {
        $completionTimes = JobStatusHistory::select(
            'workshop_jobs.job_number',
            'workshop_jobs.title',
            DB::raw('MIN(job_status_history.created_at) as start_time'),
            DB::raw('MAX(job_status_history.created_at) as end_time')
        )
            ->join('workshop_jobs', 'job_status_history.workshop_job_id', '=', 'workshop_jobs.id')
            ->where('workshop_jobs.status', 'completed')
            ->whereBetween('workshop_jobs.created_at', [$startDate, $endDate])
            ->where('job_status_history.new_status', '!=', 'cancelled')
            ->groupBy('workshop_jobs.id', 'workshop_jobs.job_number', 'workshop_jobs.title')
            ->get()
            ->map(function ($job) {
                $start = Carbon::parse($job->start_time);
                $end = Carbon::parse($job->end_time);
                $days = $start->diffInDays($end);
                
                return [
                    'job_number' => $job->job_number,
                    'title' => $job->title,
                    'completion_days' => $days,
                    'start_time' => $job->start_time,
                    'end_time' => $job->end_time,
                ];
            });

        $avgCompletionTime = $completionTimes->avg('completion_days');
        $fastestJob = $completionTimes->sortBy('completion_days')->first();
        $slowestJob = $completionTimes->sortByDesc('completion_days')->first();

        return [
            'average_days' => round($avgCompletionTime, 1),
            'fastest_job' => $fastestJob ? [
                'job_number' => $fastestJob['job_number'],
                'title' => $fastestJob['title'],
                'days' => $fastestJob['completion_days'],
            ] : null,
            'slowest_job' => $slowestJob ? [
                'job_number' => $slowestJob['job_number'],
                'title' => $slowestJob['title'],
                'days' => $slowestJob['completion_days'],
            ] : null,
            'distribution' => $completionTimes->groupBy(function ($job) {
                return match (true) {
                    $job['completion_days'] <= 1 => 'Same day',
                    $job['completion_days'] <= 3 => '1-3 days',
                    $job['completion_days'] <= 7 => '4-7 days',
                    $job['completion_days'] <= 14 => '8-14 days',
                    default => '15+ days',
                };
            })->map->count()->toArray(),
        ];
    }

    private function getMonthlyRevenue(Carbon $startDate, Carbon $endDate): array
    {
        // This is a placeholder for revenue calculation
        // In a real implementation, you'd calculate based on job costs, invoices, etc.
        return [
            'total_revenue' => 0,
            'monthly_breakdown' => [],
            'growth_rate' => 0,
        ];
    }

    private function getStatusLabel(\App\Enums\JobStatus|string $status): string
    {
        $statusValue = $status instanceof \App\Enums\JobStatus ? $status->value : $status;
        return match ($statusValue) {
            'draft' => 'Draf',
            'pending_assignment' => 'Menunggu Pelantikan',
            'assigned' => 'Dilantik',
            'in_progress' => 'Dalam Proses',
            'inspection' => 'Pemeriksaan',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => ucfirst(str_replace('_', ' ', $statusValue)),
        };
    }

    private function getCustomerTypeLabel(string $type): string
    {
        return match ($type) {
            'individual' => 'Individu',
            'government' => 'Kerajaan',
            'corporate' => 'Korporat',
            default => ucfirst($type),
        };
    }
}