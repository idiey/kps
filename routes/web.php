<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InspectionController;
use App\Http\Controllers\JobAssignmentController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\JobNoteController;
use App\Http\Controllers\KewPA10Controller;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\RepairCompletionController;
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

    // Jobs Resource Routes
    Route::resource('jobs', JobController::class);

    // Job Status Update
    Route::patch('jobs/{job}/status', [JobController::class, 'updateStatus'])
        ->name('jobs.update-status');

    // Job Timeline
    Route::get('jobs/{job}/timeline', [JobController::class, 'timeline'])
        ->name('jobs.timeline');

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

    // KEW.PA-10 Management
    Route::resource('kew-pa-10', KewPA10Controller::class);

    // KEW.PA-10 Workflow Actions
    Route::post('kew-pa-10/{kewPA10}/verify', [KewPA10Controller::class, 'verify'])
        ->name('kew-pa-10.verify');

    Route::post('kew-pa-10/{kewPA10}/create-job', [KewPA10Controller::class, 'createJob'])
        ->name('kew-pa-10.create-job');

    Route::get('jobs/{job}/prepare-return', [KewPA10Controller::class, 'prepareReturn'])
        ->name('jobs.prepare-return');

    Route::post('jobs/{job}/mark-returned', [KewPA10Controller::class, 'markReturned'])
        ->name('jobs.mark-returned');

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
});

require __DIR__.'/settings.php';
