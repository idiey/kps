<?php

use App\Models\User;
use App\Models\WorkshopJob;
use App\Enums\JobStatus;
use App\Enums\JobMode;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Ensure roles exist
    $roles = ['pentadbiran', 'penyelia', 'juruteknik', 'pemeriksa', 'kaunter'];
    foreach ($roles as $roleName) {
        if (!Role::where('name', $roleName)->exists()) {
            Role::create(['name' => $roleName, 'guard_name' => 'web']);
        }
    }

    $this->admin = User::factory()->create(['role' => 'pentadbiran']);
    $this->admin->assignRole('pentadbiran');

    $this->supervisor = User::factory()->create(['role' => 'penyelia']);
    $this->supervisor->assignRole('penyelia');

    $this->technician = User::factory()->create(['role' => 'juruteknik']);
    $this->technician->assignRole('juruteknik');

    $this->inspector = User::factory()->create(['role' => 'pemeriksa']);
    $this->inspector->assignRole('pemeriksa');

    $this->frontdesk = User::factory()->create(['role' => 'kaunter']);
    $this->frontdesk->assignRole('kaunter');
});


test('supervisor can perform kew pa 10 lifecycle', function () {
    // 1. Create Job
    $job = WorkshopJob::factory()->create([
        'job_mode' => JobMode::KEW_PA_10,
        'status' => JobStatus::NEW,
        'kew_vehicle_registration' => 'ABC1234',
    ]);

    // 2. Inspection -> Approval Pending (Simulate Inspector work)
    $job->update([
        'status' => JobStatus::INSPECTION_IN_PROGRESS,
        'kew_inspector_name' => 'Inspector Gadget',
        'kew_inspector_ic' => '900101-10-1234',
        'kew_findings' => 'Everything looks good.',
        'kew_recommendations' => 'Approve it.',
    ]);

    // 3. Supervisor Approves
    $response = $this->actingAs($this->supervisor)
        ->patch(route('jobs.update-status', $job), [
            'status' => JobStatus::INSPECTION_APPROVED->value,
            'notes' => 'Approved by Supervisor',
        ]);

    $response->assertRedirect();
    expect($job->fresh()->status)->toBe(JobStatus::INSPECTION_APPROVED);
});


test('supervisor can reject kew pa 10 lifecycle', function () {
    $job = WorkshopJob::factory()->create([
        'job_mode' => JobMode::KEW_PA_10,
        'status' => JobStatus::INSPECTION_IN_PROGRESS,
        'kew_inspector_name' => 'Inspector Gadget',
        'kew_inspector_ic' => '900101-10-1234',
        'kew_findings' => 'Bad condition.',
        'kew_recommendations' => 'Reject it.',
    ]);

    $response = $this->actingAs($this->supervisor)
        ->patch(route('jobs.update-status', $job), [
            'status' => JobStatus::INSPECTION_REJECTED->value,
            'notes' => 'Rejected',
        ]);

    $response->assertRedirect();
    expect($job->fresh()->status)->toBe(JobStatus::INSPECTION_REJECTED);
});

test('inspector can view job details', function () {
    $job = WorkshopJob::factory()->create([
        'job_mode' => JobMode::KEW_PA_10,
        'status' => JobStatus::PENDING_INSPECTION,
    ]);

    $response = $this->actingAs($this->inspector)
        ->get(route('jobs.show', $job));

    $response->assertOk();
});

test('assigned user can view job regardless of role', function () {
    $job = WorkshopJob::factory()->create([
        'job_mode' => JobMode::NORMAL,
        'assigned_to' => $this->inspector->id,
    ]);

    $response = $this->actingAs($this->inspector)
        ->get(route('jobs.show', $job));

    $response->assertOk();
});


test('supervisor can assign technician to normal job', function () {
    $job = WorkshopJob::factory()->create([
        'job_mode' => JobMode::NORMAL,
        'status' => JobStatus::NEW,
    ]);

    expect($this->supervisor->can('assign', $job))->toBeTrue();
});


test('technician cannot assign jobs', function () {
    $job = WorkshopJob::factory()->create([
        'job_mode' => JobMode::NORMAL,
        'status' => JobStatus::NEW,
    ]);

    expect($this->technician->can('assign', $job))->toBeFalse();
});


test('verify normal job happy path', function () {
    // 1. Creation
    $job = WorkshopJob::factory()->create(['status' => JobStatus::NEW]);

    // 2. Transition to IN_PROGRESS (assigned technician)
    $job->assigned_to = $this->technician->id;
    $job->save();

    $this->actingAs($this->technician)
        ->patch(route('jobs.update-status', $job), [
            'status' => JobStatus::IN_PROGRESS->value
        ])->assertRedirect();

    expect($job->fresh()->status)->toBe(JobStatus::IN_PROGRESS);

    // 3. Transition to COMPLETED
    $this->actingAs($this->technician)
        ->patch(route('jobs.update-status', $job), [
            'status' => JobStatus::COMPLETED->value
        ])->assertRedirect();
        
    expect($job->fresh()->status)->toBe(JobStatus::COMPLETED);
});
