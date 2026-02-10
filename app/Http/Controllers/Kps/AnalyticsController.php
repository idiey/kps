<?php

namespace App\Http\Controllers\Kps;

use App\Http\Controllers\Controller;
use App\Models\Kps\Debt;
use App\Models\Kps\MonthlyDeduction;
use App\Models\Kps\Site;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AnalyticsController extends Controller
{
    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Site::class);

        $month = now()->startOfMonth()->toDateString();

        $stats = [
            'total_outstanding' => Debt::sum('balance'),
            'total_deductions_this_month' => MonthlyDeduction::where('month', $month)->sum('amount'),
            'total_unallocated_this_month' => MonthlyDeduction::where('month', $month)->sum('unallocated_amount'),
        ];

        return Inertia::render('Kps/Analytics', [
            'stats' => $stats,
            'month' => $month,
        ]);
    }
}
