<?php

use App\Models\Customer;
use App\Models\User;
use App\Models\WorkshopJob;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'pentadbiran']);
    $this->technician = User::factory()->create(['role' => 'juruteknik']);
    $this->technician2 = User::factory()->create(['role' => 'juruteknik']);
    $this->customer = Customer::factory()->create();
});

test('admin can assign job to technician', function () {
    $job = WorkshopJob::factory()->create(['customer_id' => $this->customer->id]);

    $data = [
        'assigned_to' => $this->technician->id,
        'notes' => 'Assigned to John for engine work',
    ];

    $response = $this->actingAs($this->admin)->post(route('jobs.assign', $job), $data);

    $response->assertRedirect();
    $this->assertDatabaseHas('workshop_jobs', [
        'id' => $job->id,
        'assigned_to' => $this->technician->id,
    ]);
    $this->assertDatabaseHas('job_assignments', [
        'workshop_job_id' => $job->id,
        'assigned_to' => $this->technician->id,
        'assigned_by' => $this->admin->id,
        'is_current' => true,
    ]);
});

test('technician cannot assign jobs', function () {
    $job = WorkshopJob::factory()->create(['customer_id' => $this->customer->id]);

    $data = [
        'assigned_to' => $this->technician2->id,
    ];

    $response = $this->actingAs($this->technician)->post(route('jobs.assign', $job), $data);

    $response->assertStatus(403);
});

test('admin can reassign job to different technician', function () {
    $job = WorkshopJob::factory()->create([
        'customer_id' => $this->customer->id,
        'assigned_to' => $this->technician->id,
    ]);

    // Create initial assignment
    $job->assignments()->create([
        'assigned_to' => $this->technician->id,
        'assigned_by' => $this->admin->id,
        'assigned_at' => now(),
        'is_current' => true,
    ]);

    $data = [
        'assigned_to' => $this->technician2->id,
        'notes' => 'Reassigning due to workload',
    ];

    $response = $this->actingAs($this->admin)->post(route('jobs.assign', $job), $data);

    $response->assertRedirect();
    $this->assertDatabaseHas('workshop_jobs', [
        'id' => $job->id,
        'assigned_to' => $this->technician2->id,
    ]);

    // Check old assignment is marked as not current
    $this->assertDatabaseHas('job_assignments', [
        'workshop_job_id' => $job->id,
        'assigned_to' => $this->technician->id,
        'is_current' => false,
    ]);

    // Check new assignment is current
    $this->assertDatabaseHas('job_assignments', [
        'workshop_job_id' => $job->id,
        'assigned_to' => $this->technician2->id,
        'is_current' => true,
    ]);
});

test('assignment requires valid technician', function () {
    $job = WorkshopJob::factory()->create(['customer_id' => $this->customer->id]);

    $data = [
        'assigned_to' => 999999,
    ];

    $response = $this->actingAs($this->admin)->post(route('jobs.assign', $job), $data);

    $response->assertSessionHasErrors('assigned_to');
});

test('admin can view assignment history', function () {
    $job = WorkshopJob::factory()->create(['customer_id' => $this->customer->id]);

    // Create assignment history
    $job->assignments()->create([
        'assigned_to' => $this->technician->id,
        'assigned_by' => $this->admin->id,
        'assigned_at' => now()->subDays(2),
        'is_current' => false,
    ]);

    $job->assignments()->create([
        'assigned_to' => $this->technician2->id,
        'assigned_by' => $this->admin->id,
        'assigned_at' => now(),
        'is_current' => true,
    ]);

    $response = $this->actingAs($this->admin)->get(route('jobs.assignment-history', $job));

    $response->assertStatus(200)
        ->assertJsonCount(2, 'assignments');
});
