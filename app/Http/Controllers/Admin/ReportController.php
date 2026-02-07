<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:pentadbiran');
    }

    /**
     * Display the reports index page
     */
    public function index()
    {
        return Inertia::render('Admin/Reports/Index');
    }

    /**
     * Generate Job Reports
     */
    public function jobReport(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', 'in:daily,weekly,monthly,annual'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'status' => ['nullable', 'string'],
            'job_mode' => ['nullable', 'in:normal,kew_pa_10'],
            'format' => ['required', 'in:pdf,excel,csv'],
        ]);

        // Build query
        $query = Job::with(['customer', 'assignedTo', 'vehicle'])
            ->whereBetween('created_at', [
                Carbon::parse($validated['start_date'])->startOfDay(),
                Carbon::parse($validated['end_date'])->endOfDay(),
            ]);

        if (!empty($validated['status'])) {
            $query->where('status', $validated['status']);
        }

        if (!empty($validated['job_mode'])) {
            $query->where('job_mode', $validated['job_mode']);
        }

        $jobs = $query->orderBy('created_at', 'desc')->get();

        // Prepare data for export
        $data = [
            'title' => 'Job Report - ' . ucfirst($validated['type']),
            'period' => Carbon::parse($validated['start_date'])->format('d/m/Y') . ' - ' . 
                       Carbon::parse($validated['end_date'])->format('d/m/Y'),
            'generated_at' => now()->format('d/m/Y H:i:s'),
            'jobs' => $jobs,
            'summary' => [
                'total_jobs' => $jobs->count(),
                'completed' => $jobs->where('status', 'completed')->count(),
                'in_progress' => $jobs->whereIn('status', ['assigned', 'inspected', 'approved'])->count(),
                'pending' => $jobs->where('status', 'pending')->count(),
                'normal_jobs' => $jobs->where('job_mode', 'normal')->count(),
                'kew_pa_10_jobs' => $jobs->where('job_mode', 'kew_pa_10')->count(),
            ],
        ];

        return $this->exportReport($data, 'job_report', $validated['format']);
    }

    /**
     * Generate Customer Reports
     */
    public function customerReport(Request $request)
    {
        $validated = $request->validate([
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'customer_type' => ['nullable', 'in:government,private'],
            'format' => ['required', 'in:pdf,excel,csv'],
        ]);

        // Get customers with their job count
        $query = Customer::withCount(['jobs' => function ($q) use ($validated) {
            $q->whereBetween('created_at', [
                Carbon::parse($validated['start_date'])->startOfDay(),
                Carbon::parse($validated['end_date'])->endOfDay(),
            ]);
        }]);

        if (!empty($validated['customer_type'])) {
            $query->where('customer_type', $validated['customer_type']);
        }

        $customers = $query->orderBy('jobs_count', 'desc')->get();

        $data = [
            'title' => 'Customer Report',
            'period' => Carbon::parse($validated['start_date'])->format('d/m/Y') . ' - ' . 
                       Carbon::parse($validated['end_date'])->format('d/m/Y'),
            'generated_at' => now()->format('d/m/Y H:i:s'),
            'customers' => $customers,
            'summary' => [
                'total_customers' => $customers->count(),
                'government' => $customers->where('customer_type', 'government')->count(),
                'private' => $customers->where('customer_type', 'private')->count(),
                'total_jobs' => $customers->sum('jobs_count'),
            ],
        ];

        return $this->exportReport($data, 'customer_report', $validated['format']);
    }

    /**
     * Generate Performance Reports
     */
    public function performanceReport(Request $request)
    {
        $validated = $request->validate([
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'format' => ['required', 'in:pdf,excel,csv'],
        ]);

        $startDate = Carbon::parse($validated['start_date'])->startOfDay();
        $endDate = Carbon::parse($validated['end_date'])->endOfDay();

        $jobs = Job::whereBetween('created_at', [$startDate, $endDate])->get();

        // Calculate average completion time for completed jobs
        $completedJobs = $jobs->where('status', 'completed');
        $avgCompletionDays = $completedJobs->count() > 0 
            ? $completedJobs->avg(function ($job) {
                return $job->created_at->diffInDays($job->updated_at);
            })
            : 0;

        $data = [
            'title' => 'Performance Report',
            'period' => $startDate->format('d/m/Y') . ' - ' . $endDate->format('d/m/Y'),
            'generated_at' => now()->format('d/m/Y H:i:s'),
            'jobs' => $jobs,
            'metrics' => [
                'total_jobs' => $jobs->count(),
                'completed_jobs' => $completedJobs->count(),
                'completion_rate' => $jobs->count() > 0 
                    ? round(($completedJobs->count() / $jobs->count()) * 100, 2) 
                    : 0,
                'avg_completion_days' => round($avgCompletionDays, 1),
                'jobs_per_day' => round($jobs->count() / max(1, $startDate->diffInDays($endDate)), 2),
            ],
        ];

        return $this->exportReport($data, 'performance_report', $validated['format']);
    }

    /**
     * Export report in the requested format
     */
    private function exportReport(array $data, string $filename, string $format)
    {
        $timestamp = now()->format('Y-m-d_His');
        $fullFilename = "{$filename}_{$timestamp}";

        switch ($format) {
            case 'pdf':
                $pdf = Pdf::loadView('reports.pdf', $data);
                return $pdf->download("{$fullFilename}.pdf");

            case 'excel':
                return Excel::download(
                    new \App\Exports\GeneralReportExport($data),
                    "{$fullFilename}.xlsx"
                );

            case 'csv':
                return Excel::download(
                    new \App\Exports\GeneralReportExport($data),
                    "{$fullFilename}.csv",
                    \Maatwebsite\Excel\Excel::CSV
                );

            default:
                abort(400, 'Invalid export format');
        }
    }
}
