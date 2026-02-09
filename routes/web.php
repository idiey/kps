<?php

use App\Http\Controllers\Admin\RoleManagementController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InspectionController;
use App\Http\Controllers\JobAssignmentController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\JobNoteController;
use App\Http\Controllers\KewApprovalController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\RepairCompletionController;
use App\Http\Controllers\JobAnalyticsController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Workload Dashboard
    Route::get('dashboard/workload', [DashboardController::class, 'workload'])
        ->name('dashboard.workload');

    Route::get('dashboard/my-jobs', [DashboardController::class, 'myJobs'])
        ->name('dashboard.my-jobs');

    Route::get('dashboard/statistics', [DashboardController::class, 'statistics'])
        ->name('dashboard.statistics');

    // Analytics Dashboard
    Route::get('analytics', [JobAnalyticsController::class, 'index'])
        ->name('analytics.index');

    // Job Mode Selection & Creation Routes (NEW - Architecture Simplification)
    // Place BEFORE resource routes to avoid conflicts
    Route::prefix('jobs')->group(function () {
        // Job mode selector
        Route::get('select-mode', [JobController::class, 'selectMode'])
            ->name('jobs.select-mode');
        
        // KEW.PA-10 job creation
        Route::get('create/kew', [JobController::class, 'createKew'])
            ->name('jobs.create.kew');
        
        // Normal job creation
        Route::get('create/normal', [JobController::class, 'createNormal'])
            ->name('jobs.create.normal');
    });

    // Jobs Resource Routes
    Route::resource('jobs', JobController::class);

    // Job Status Update
    Route::patch('jobs/{job}/status', [JobController::class, 'updateStatus'])
        ->name('jobs.update-status');

    // Job Timeline
    Route::get('jobs/{job}/timeline', [JobController::class, 'timeline'])
        ->name('jobs.timeline');

    // KEW.PA-10 Approval Routes (NEW - Architecture Simplification)
    Route::prefix('jobs/kew')->name('jobs.kew.')->group(function () {
        // Approve inspection
        Route::post('{job}/approve', [KewApprovalController::class, 'approve'])
            ->middleware('role:penyelia|pentadbiran|pelulus')
            ->name('approve');
        
        // Reject inspection (requires reason)
        Route::post('{job}/reject', [KewApprovalController::class, 'reject'])
            ->middleware('role:penyelia|pentadbiran|pelulus')
            ->name('reject');
        
        // View approval history
        Route::get('{job}/history', [KewApprovalController::class, 'history'])
            ->name('history');
        
        // List pending approvals (supervisor dashboard)
        Route::get('pending', [KewApprovalController::class, 'index'])
            ->middleware('role:penyelia|pentadbiran|pelulus')
            ->name('pending');
    });

    // Job Assignment Routes
    Route::post('jobs/{job}/assign', [JobAssignmentController::class, 'store'])
        ->name('jobs.assign');

    Route::get('jobs/{job}/assignment-history', [JobAssignmentController::class, 'history'])
        ->name('jobs.assignment-history');

    // Job Notes Routes
    Route::get('jobs/{job}/notes', [JobNoteController::class, 'index'])
        ->name('jobs.notes.index');

    Route::post('jobs/{job}/notes', [JobNoteController::class, 'store'])
        ->name('jobs.notes.store');

    Route::put('jobs/{job}/notes/{note}', [JobNoteController::class, 'update'])
        ->name('jobs.notes.update');

    Route::delete('jobs/{job}/notes/{note}', [JobNoteController::class, 'destroy'])
        ->name('jobs.notes.destroy');

    // Customers Resource Routes
    Route::resource('customers', CustomerController::class);

    // Customer Search (autocomplete)
    Route::get('customers/search/autocomplete', [CustomerController::class, 'search'])
        ->name('customers.search');

    // Inspections
    Route::get('jobs/{job}/inspections/create', [InspectionController::class, 'create'])
        ->name('inspections.create');

    Route::post('jobs/{job}/inspections', [InspectionController::class, 'store'])
        ->name('inspections.store');

    Route::resource('inspections', InspectionController::class)
        ->except(['create', 'store']);

    // Inspection Workflow Actions
    Route::post('inspections/{inspection}/approve', [InspectionController::class, 'approve'])
        ->name('inspections.approve');

    Route::post('inspections/{inspection}/approve-with-conditions', [InspectionController::class, 'approveWithConditions'])
        ->name('inspections.approve-conditions');

    Route::post('inspections/{inspection}/reject', [InspectionController::class, 'reject'])
        ->name('inspections.reject');

    // Photos
    Route::get('jobs/{job}/photos', [PhotoController::class, 'index'])
        ->name('jobs.photos.index');

    Route::post('jobs/{job}/photos', [PhotoController::class, 'store'])
        ->name('jobs.photos.store');

    Route::post('jobs/{job}/photos/bulk', [PhotoController::class, 'bulkUpload'])
        ->name('jobs.photos.bulk');

    Route::get('jobs/{job}/photos/stage/{stage}', [PhotoController::class, 'byStage'])
        ->name('jobs.photos.by-stage');

    Route::delete('photos/{photo}', [PhotoController::class, 'destroy'])
        ->name('photos.destroy');

    Route::post('photos/{photo}/toggle-public', [PhotoController::class, 'togglePublic'])
        ->name('photos.toggle-public');

    // Repair Completion
    Route::get('jobs/{job}/completion/create', [RepairCompletionController::class, 'create'])
        ->name('completion.create');

    Route::post('jobs/{job}/completion', [RepairCompletionController::class, 'store'])
        ->name('completion.store');

    Route::resource('completion', RepairCompletionController::class)
        ->except(['create', 'store', 'index'])
        ->parameters(['completion' => 'report']);

    // Completion Workflow Actions
    Route::post('completion/{report}/sign', [RepairCompletionController::class, 'sign'])
        ->name('completion.sign');

    Route::post('completion/{report}/submit', [RepairCompletionController::class, 'submitForReview'])
        ->name('completion.submit');

    Route::post('completion/{report}/parts', [RepairCompletionController::class, 'addPart'])
        ->name('completion.add-part');

    Route::delete('completion/{report}/parts/{partIndex}', [RepairCompletionController::class, 'removePart'])
        ->name('completion.remove-part');



    // Admin Routes (Pentadbiran only)
    Route::prefix('admin')->name('admin.')->group(function () {
        // Workshop Management
        Route::resource('workshops', \App\Http\Controllers\Admin\WorkshopController::class);
        Route::post('workshops/{workshop}/toggle-status', [\App\Http\Controllers\Admin\WorkshopController::class, 'toggleStatus'])
            ->name('workshops.toggle-status');

        // Workshop User Management
        Route::get('workshops/{workshop}/users', [\App\Http\Controllers\Admin\WorkshopUserController::class, 'index'])
            ->name('workshops.users.index');
        Route::post('workshops/{workshop}/users', [\App\Http\Controllers\Admin\WorkshopUserController::class, 'store'])
            ->name('workshops.users.store');
        Route::patch('workshops/{workshop}/users/{user}', [\App\Http\Controllers\Admin\WorkshopUserController::class, 'update'])
            ->name('workshops.users.update');
        Route::delete('workshops/{workshop}/users/{user}', [\App\Http\Controllers\Admin\WorkshopUserController::class, 'destroy'])
            ->name('workshops.users.destroy');

        // Workshop Analytics
        Route::get('workshops/{workshop}/analytics', [\App\Http\Controllers\Admin\WorkshopAnalyticsController::class, 'show'])
            ->name('workshops.analytics');

        // Site-Scoped Jobs (displays jobs filtered by site with dual sidebar)
        Route::get('workshops/{workshop}/jobs', [\App\Http\Controllers\Admin\WorkshopController::class, 'jobs'])
            ->name('workshops.jobs');


        // Role and Permission Management
        Route::resource('roles', RoleManagementController::class);
        Route::get('roles-permissions', [RoleManagementController::class, 'permissions'])
            ->name('roles.permissions');
        Route::post('roles-permissions/update', [RoleManagementController::class, 'updatePermissionMatrix'])
            ->name('roles.permissions.update');
        Route::patch('roles/{role}/permissions', [RoleManagementController::class, 'updatePermissions'])
            ->name('roles.update-permissions');
        Route::post('roles/{role}/deactivate', [RoleManagementController::class, 'deactivate'])
            ->name('roles.deactivate');
        Route::post('roles/{role}/activate', [RoleManagementController::class, 'activate'])
            ->name('roles.activate');
        Route::get('roles/{role}/users', [RoleManagementController::class, 'getUsers'])
            ->name('roles.users');
        Route::post('roles/{role}/users', [RoleManagementController::class, 'assignUsers'])
            ->name('roles.assign-users');
        Route::delete('roles/{role}/users', [RoleManagementController::class, 'removeUsers'])
            ->name('roles.remove-users');

        // User Management
        Route::resource('users', \App\Http\Controllers\Admin\UserManagementController::class);
        Route::patch('users/{user}/toggle-activation', [\App\Http\Controllers\Admin\UserManagementController::class, 'toggleActivation'])
            ->name('users.toggle-activation');

        // Reports
        Route::get('reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])
            ->name('reports.index');
        Route::post('reports/jobs', [\App\Http\Controllers\Admin\ReportController::class, 'jobReport'])
            ->name('reports.jobs');
        Route::post('reports/customers', [\App\Http\Controllers\Admin\ReportController::class, 'customerReport'])
            ->name('reports.customers');
        Route::post('reports/performance', [\App\Http\Controllers\Admin\ReportController::class, 'performanceReport'])
            ->name('reports.performance');

        // Asset Management
        Route::resource('assets', \App\Http\Controllers\Admin\AssetController::class);

        // Parts Inventory
        Route::resource('inventory', \App\Http\Controllers\Admin\InventoryController::class);
        Route::post('inventory/{part}/adjust-stock', [\App\Http\Controllers\Admin\InventoryController::class, 'adjustStock'])
            ->name('inventory.adjust-stock');

        // Settings
        Route::get('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])
            ->name('settings.index');
        Route::post('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'store'])
            ->name('settings.store');
        Route::patch('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])
            ->name('settings.update');
        Route::delete('settings/{setting}', [\App\Http\Controllers\Admin\SettingsController::class, 'destroy'])
            ->name('settings.destroy');
        Route::post('settings/initialize', [\App\Http\Controllers\Admin\SettingsController::class, 'initializeDefaults'])
            ->name('settings.initialize');
    });
});

require __DIR__.'/settings.php';
require __DIR__.'/kps.php';
