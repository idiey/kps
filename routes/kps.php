<?php

use App\Http\Controllers\Kps\AllocationReviewController;
use App\Http\Controllers\Kps\AuditLogController;
use App\Http\Controllers\Kps\AnalyticsController;
use App\Http\Controllers\Kps\DashboardController;
use App\Http\Controllers\Kps\DebtController;
use App\Http\Controllers\Kps\MonthlyDeductionController;
use App\Http\Controllers\Kps\PenerokaController;
use App\Http\Controllers\Kps\ProfileController;
use App\Http\Controllers\Kps\ReportController;
use App\Http\Controllers\Kps\SiteController;
use App\Http\Middleware\EnsureKpsSiteContext;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', EnsureKpsSiteContext::class])
    ->prefix('kps')
    ->name('kps.')
    ->group(function () {
        Route::get('/', fn () => redirect()->route('kps.dashboard'))
            ->name('home');

        Route::get('dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('analytics', [AnalyticsController::class, 'index'])
            ->name('analytics');

        Route::get('profile', [ProfileController::class, 'edit'])
            ->name('profile.edit');
        Route::patch('profile', [ProfileController::class, 'update'])
            ->name('profile.update');

        Route::resource('sites', SiteController::class);

        // Site-scoped routes
        Route::prefix('sites/{site}')->group(function () {
            // Peneroka
            Route::get('peneroka', [PenerokaController::class, 'index'])->name('peneroka.index');
            Route::get('peneroka/create', [PenerokaController::class, 'create'])->name('peneroka.create');
            Route::post('peneroka', [PenerokaController::class, 'store'])->name('peneroka.store');
            Route::get('peneroka/{peneroka}/edit', [PenerokaController::class, 'edit'])->name('peneroka.edit');
            Route::put('peneroka/{peneroka}', [PenerokaController::class, 'update'])->name('peneroka.update');
            Route::delete('peneroka/{peneroka}', [PenerokaController::class, 'destroy'])->name('peneroka.destroy');

            // Hutang
            Route::get('hutang', [DebtController::class, 'index'])->name('hutang.index');
            Route::get('hutang/create', [DebtController::class, 'create'])->name('hutang.create');
            Route::post('hutang', [DebtController::class, 'store'])->name('hutang.store');
            Route::get('hutang/{debt}/edit', [DebtController::class, 'edit'])->name('hutang.edit');
            Route::put('hutang/{debt}', [DebtController::class, 'update'])->name('hutang.update');
            Route::delete('hutang/{debt}', [DebtController::class, 'destroy'])->name('hutang.destroy');

            // Potongan Bulanan
            Route::get('potongan', [MonthlyDeductionController::class, 'index'])->name('potongan.index');
            Route::get('potongan/create', [MonthlyDeductionController::class, 'create'])->name('potongan.create');
            Route::post('potongan', [MonthlyDeductionController::class, 'store'])->name('potongan.store');
            Route::get('potongan/bulk', [MonthlyDeductionController::class, 'createBulk'])->name('potongan.bulk');
            Route::post('potongan/bulk', [MonthlyDeductionController::class, 'storeBulk'])->name('potongan.bulk.store');
            Route::get('potongan/bulk/template', [MonthlyDeductionController::class, 'downloadBulkTemplate'])->name('potongan.bulk.template');
            Route::post('potongan/bulk/upload', [MonthlyDeductionController::class, 'uploadBulkExcel'])->name('potongan.bulk.upload');

            // Allocation Review
            Route::get('allocations', [AllocationReviewController::class, 'index'])->name('allocations.index');
            Route::get('allocations/{deduction}', [AllocationReviewController::class, 'show'])->name('allocations.show');
            Route::post('allocations/{deduction}/reallocate', [AllocationReviewController::class, 'reallocate'])->name('allocations.reallocate');
            Route::post('allocations/close-month', [AllocationReviewController::class, 'closeMonth'])->name('allocations.close-month');

            // Reports
            Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
            Route::get('reports/export/csv', [ReportController::class, 'exportSiteCsv'])->name('reports.export.csv');
            Route::get('reports/peneroka/{peneroka}', [ReportController::class, 'penerokaStatement'])->name('reports.statement');
            Route::get('reports/peneroka/{peneroka}/export/csv', [ReportController::class, 'exportPenerokaStatementCsv'])->name('reports.statement.export.csv');
            Route::get('reports/peneroka/{peneroka}/export/pdf', [ReportController::class, 'exportPenerokaStatementPdf'])->name('reports.statement.export.pdf');

            // Audit Trail
            Route::get('audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
        });
    });
