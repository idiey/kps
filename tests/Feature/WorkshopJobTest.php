<?php

use App\Enums\JobStatus;
use App\Models\Customer;
use App\Models\User;
use App\Models\WorkshopJob;
use App\Services\JobAssignmentService;
use App\Services\JobNoteService;
use App\Services\JobService;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'pentadbiran']);
    $this->technician = User::factory()->create(['role' => 'juruteknik']);
    $this->customer = Customer::factory()->create();
});

test('it can create a job with auto generated job number', function () {
    $job = WorkshopJob::create([
        'customer_id' => $this->customer->id,
        'title' => 'Test Job',
        'description' => 'Test Description',
    ]);

    expect($job->job_number)->not->toBeNull()
        ->and($job->job_number)->toStartWith('WJ-')
        ->and($job->status)->toBe(JobStatus::NEW);
});

test('it validates status transitions', function () {
    $job = WorkshopJob::factory()->create([
        'customer_id' => $this->customer->id,
        'status' => JobStatus::NEW,
    ]);

    expect($job->canTransitionTo(JobStatus::IN_PROGRESS))->toBeTrue()
        ->and($job->canTransitionTo(JobStatus::COMPLETED))->toBeFalse()
        ->and($job->canTransitionTo(JobStatus::INVOICED))->toBeFalse();
});

test('it can assign job to technician', function () {
    $this->actingAs($this->admin);

    $job = WorkshopJob::factory()->create([
        'customer_id' => $this->customer->id,
    ]);

    $service = new JobAssignmentService();
    $assignment = $service->assignJob($job, $this->technician->id, 'Initial assignment');

    expect($job->fresh()->assigned_to)->toBe($this->technician->id)
        ->and($assignment->assigned_by)->toBe($this->admin->id)
        ->and($assignment->is_current)->toBeTrue();
});

test('it can change job status with validation', function () {
    $this->actingAs($this->admin);

    $job = WorkshopJob::factory()->create([
        'customer_id' => $this->customer->id,
        'status' => JobStatus::NEW,
    ]);

    // Create initial status history manually since factory doesn't do it
    $job->statusHistories()->create([
        'user_id' => $this->admin->id,
        'from_status' => null,
        'to_status' => JobStatus::NEW,
        'changed_at' => $job->created_at,
    ]);

    $service = new JobService();
    $updatedJob = $service->changeStatus($job, JobStatus::IN_PROGRESS, 'Starting work');

    expect($updatedJob->status)->toBe(JobStatus::IN_PROGRESS)
        ->and($updatedJob->started_at)->not->toBeNull()
        ->and($updatedJob->statusHistories)->toHaveCount(2); // Initial + change
});

test('it prevents invalid status transitions', function () {
    $this->actingAs($this->admin);

    $job = WorkshopJob::factory()->create([
        'customer_id' => $this->customer->id,
        'status' => JobStatus::NEW,
    ]);

    $service = new JobService();
    $service->changeStatus($job, JobStatus::INVOICED); // Invalid transition
})->throws(InvalidArgumentException::class);

test('it can add notes to job', function () {
    $this->actingAs($this->technician);

    $job = WorkshopJob::factory()->create([
        'customer_id' => $this->customer->id,
    ]);

    $service = new JobNoteService();
    $note = $service->createNote($job, 'Test note content', false, 'general');

    expect($note->content)->toBe('Test note content')
        ->and($note->user_id)->toBe($this->technician->id)
        ->and($note->is_public)->toBeFalse();
});

test('it tracks assignment history', function () {
    $this->actingAs($this->admin);

    $technician2 = User::factory()->create(['role' => 'juruteknik']);

    $job = WorkshopJob::factory()->create([
        'customer_id' => $this->customer->id,
    ]);

    $service = new JobAssignmentService();

    // First assignment
    $service->assignJob($job, $this->technician->id);

    // Reassignment
    $service->reassignJob($job, $technician2->id);

    $history = $service->getAssignmentHistory($job);
    $currentAssignment = $history->where('is_current', true)->first();
    $previousAssignment = $history->where('is_current', false)->first();

    expect($history)->toHaveCount(2)
        ->and($job->fresh()->assigned_to)->toBe($technician2->id)
        ->and($currentAssignment->assigned_to)->toBe($technician2->id)
        ->and($previousAssignment->assigned_to)->toBe($this->technician->id);
});

test('it can filter jobs by status', function () {
    WorkshopJob::factory()->create([
        'customer_id' => $this->customer->id,
        'status' => JobStatus::NEW,
    ]);

    WorkshopJob::factory()->create([
        'customer_id' => $this->customer->id,
        'status' => JobStatus::IN_PROGRESS,
    ]);

    $service = new JobService();
    $newJobs = $service->getPaginatedJobs(['status' => JobStatus::NEW], 10);
    $inProgressJobs = $service->getPaginatedJobs(['status' => JobStatus::IN_PROGRESS], 10);

    expect($newJobs)->toHaveCount(1)
        ->and($inProgressJobs)->toHaveCount(1);
});

test('it can get jobs for technician', function () {
    WorkshopJob::factory()->create([
        'customer_id' => $this->customer->id,
        'assigned_to' => $this->technician->id,
    ]);

    WorkshopJob::factory()->create([
        'customer_id' => $this->customer->id,
        'assigned_to' => null,
    ]);

    $service = new JobService();
    $technicianJobs = $service->getJobsForTechnician($this->technician->id);

    expect($technicianJobs)->toHaveCount(1);
});

test('it detects overdue jobs', function () {
    $overdueJob = WorkshopJob::factory()->create([
        'customer_id' => $this->customer->id,
        'due_date' => now()->subDays(1),
        'status' => JobStatus::IN_PROGRESS,
    ]);

    $onTimeJob = WorkshopJob::factory()->create([
        'customer_id' => $this->customer->id,
        'due_date' => now()->addDays(1),
        'status' => JobStatus::IN_PROGRESS,
    ]);

    expect($overdueJob->isOverdue())->toBeTrue()
        ->and($onTimeJob->isOverdue())->toBeFalse();

    $service = new JobService();
    $overdueJobs = $service->getOverdueJobs();

    expect($overdueJobs)->toHaveCount(1);
});
