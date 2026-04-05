<?php

namespace App\Http\Controllers\Kps;

use App\Http\Controllers\Controller;
use App\Models\Kps\Debt;
use App\Models\Kps\MonthlyDeduction;
use App\Models\Kps\Peneroka;
use App\Models\Kps\Site;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Site::class);

        $stats = [
            'sites' => Site::count(),
            'peneroka' => Peneroka::count(),
            'total_debt_balance' => Debt::sum('balance'),
            'monthly_deductions' => MonthlyDeduction::whereDate('month', now()->startOfMonth()->toDateString())->sum('amount'),
        ];

        return Inertia::render('Kps/Dashboard', [
            'stats' => $stats,
        ]);
    }
}
