<?php

use App\Http\Controllers\Admin\RoleManagementController;
use App\Http\Controllers\Admin\TemplateController;
use App\Http\Controllers\Admin\TemplateFieldController;
use App\Http\Controllers\Admin\WorkflowController;
use App\Http\Controllers\Admin\WorkflowStatusController;
use App\Http\Controllers\Admin\WorkflowTransitionController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DynamicJobController;
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
    Route::resource('kew-pa-10', KewPA10Controller::class)
        ->parameters(['kew-pa-10' => 'kewPA10']);

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

    // Dynamic Workflow System Routes
    // Template selection and dynamic job creation
    Route::get('jobs/templates/select', [DynamicJobController::class, 'create'])
        ->name('jobs.select-template');

    Route::get('jobs/create/{template}', [DynamicJobController::class, 'create'])
        ->name('jobs.create-dynamic');

    // Workflow transitions
    Route::post('jobs/{job}/transitions/{transition}', [DynamicJobController::class, 'executeTransition'])
        ->name('jobs.execute-transition');

    // API endpoints for dynamic forms
    Route::get('api/jobs/{job}/available-transitions', [DynamicJobController::class, 'getAvailableTransitions'])
        ->name('api.jobs.available-transitions');

    Route::get('api/jobs/{job}/field-rules', [DynamicJobController::class, 'getFieldRules'])
        ->name('api.jobs.field-rules');

    Route::post('api/templates/{template}/validate', [DynamicJobController::class, 'validateFieldData'])
        ->name('api.templates.validate');

    Route::get('api/templates/{template}/schema', [DynamicJobController::class, 'getFormSchema'])
        ->name('api.templates.schema');

    Route::get('api/templates/{template}/workflows', [DynamicJobController::class, 'getWorkflows'])
        ->name('api.templates.workflows');

    // Admin Routes (Pentadbiran only)
    Route::prefix('admin')->name('admin.')->group(function () {
        // Workflow Management
        Route::resource('workflows', WorkflowController::class);
        Route::get('workflows/{workflow}/builder', [WorkflowController::class, 'builder'])
            ->name('workflows.builder');

        // Workflow Statuses
        Route::resource('workflows.statuses', WorkflowStatusController::class);
        Route::post('workflows/{workflow}/statuses/reorder', [WorkflowStatusController::class, 'reorder'])
            ->name('workflows.statuses.reorder');

        // Workflow Transitions
        Route::resource('workflows.transitions', WorkflowTransitionController::class);
        Route::post('workflows/{workflow}/transitions/reorder', [WorkflowTransitionController::class, 'reorder'])
            ->name('workflows.transitions.reorder');
        Route::patch('workflows/{workflow}/transitions/{transition}/conditions', [WorkflowTransitionController::class, 'updateConditions'])
            ->name('workflows.transitions.update-conditions');
        Route::patch('workflows/{workflow}/transitions/{transition}/actions', [WorkflowTransitionController::class, 'updateActions'])
            ->name('workflows.transitions.update-actions');

        // Template Management
        Route::resource('templates', TemplateController::class);
        Route::get('templates/{template}/workflows', [TemplateController::class, 'getWorkflows'])
            ->name('templates.workflows');
        Route::get('templates/{template}/schema', [TemplateController::class, 'getSchema'])
            ->name('templates.schema');
        Route::post('templates/{template}/workflows/{workflow}', [TemplateController::class, 'attachWorkflow'])
            ->name('templates.attach-workflow');
        Route::delete('templates/{template}/workflows/{workflow}', [TemplateController::class, 'detachWorkflow'])
            ->name('templates.detach-workflow');

        // Template Fields
        Route::resource('templates.fields', TemplateFieldController::class);
        Route::post('templates/{template}/fields/reorder', [TemplateFieldController::class, 'reorder'])
            ->name('templates.fields.reorder');
        Route::post('templates/{template}/fields/{field}/duplicate', [TemplateFieldController::class, 'duplicate'])
            ->name('templates.fields.duplicate');
        Route::get('templates/{template}/fields/{field}/preview', [TemplateFieldController::class, 'preview'])
            ->name('templates.fields.preview');
        Route::post('templates/{template}/fields/{field}/test-formula', [TemplateFieldController::class, 'testFormula'])
            ->name('templates.fields.test-formula');

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
    });
});

require __DIR__.'/settings.php';
