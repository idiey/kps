<?php

use App\Enums\JobPriority;
use App\Enums\JobStatus;
use App\Models\Customer;
use App\Models\User;
use App\Models\WorkshopJob;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'pentadbiran']);
    $this->technician = User::factory()->create(['role' => 'juruteknik']);
    $this->customer = Customer::factory()->create();
    $this->adminRole = ensureRole('pentadbiran');
    $this->technicianRole = ensureRole('juruteknik');
    $this->admin->syncRoles([$this->adminRole->name]);
    $this->technician->syncRoles([$this->technicianRole->name]);
    $this->workflow = createWorkflowWithRoles([$this->adminRole->id, $this->technicianRole->id]);
    $this->jobDefaults = [
        'workflow_id' => $this->workflow->id,
        'current_workflow_status_id' => $this->workflow->initialStatus()?->id,
    ];
});

// Index Tests
test('admin can view jobs index page', function () {
    WorkshopJob::factory()->count(3)->create($this->jobDefaults + ['customer_id' => $this->customer->id]);

    $response = $this->actingAs($this->admin)->get(route('jobs.index'));

    $response->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Jobs/Index')
            ->has('jobs.data', 3)
        );
});

test('technician can view jobs index page', function () {
    $response = $this->actingAs($this->technician)->get(route('jobs.index'));

    $response->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Jobs/Index')
        );
});

test('guest cannot view jobs index', function () {
    $response = $this->get(route('jobs.index'));

    $response->assertRedirect(route('login'));
});

test('jobs can be filtered by status', function () {
    WorkshopJob::factory()->create($this->jobDefaults + ['customer_id' => $this->customer->id, 'status' => JobStatus::NEW]);
    WorkshopJob::factory()->create($this->jobDefaults + ['customer_id' => $this->customer->id, 'status' => JobStatus::IN_PROGRESS]);

    $response = $this->actingAs($this->admin)->get(route('jobs.index', ['status' => JobStatus::NEW->value]));

    $response->assertInertia(fn (Assert $page) => $page
        ->component('Jobs/Index')
        ->has('jobs.data', 1)
        ->where('jobs.data.0.status', JobStatus::NEW->value)
    );
});

test('jobs can be filtered by assigned technician', function () {
    WorkshopJob::factory()->create($this->jobDefaults + ['customer_id' => $this->customer->id, 'assigned_to' => $this->technician->id]);
    WorkshopJob::factory()->create($this->jobDefaults + ['customer_id' => $this->customer->id, 'assigned_to' => null]);

    $response = $this->actingAs($this->admin)->get(route('jobs.index', ['assigned_to' => $this->technician->id]));

    $response->assertInertia(fn (Assert $page) => $page
        ->component('Jobs/Index')
        ->has('jobs.data', 1)
        ->where('jobs.data.0.assigned_to', $this->technician->id)
    );
});

test('jobs can be searched', function () {
    WorkshopJob::factory()->create($this->jobDefaults + [
        'customer_id' => $this->customer->id,
        'title' => 'Engine Repair',
        'vehicle_registration' => 'ABC1234',
    ]);
    WorkshopJob::factory()->create($this->jobDefaults + [
        'customer_id' => $this->customer->id,
        'title' => 'Brake Service',
    ]);

    $response = $this->actingAs($this->admin)->get(route('jobs.index', ['search' => 'ABC1234']));

    $response->assertInertia(fn (Assert $page) => $page
        ->has('jobs.data', 1)
        ->where('jobs.data.0.title', 'Engine Repair')
    );
});

// Show Tests
test('admin can view job details', function () {
    $job = WorkshopJob::factory()->create($this->jobDefaults + ['customer_id' => $this->customer->id]);

    $response = $this->actingAs($this->admin)->get(route('jobs.show', $job));

    $response->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Jobs/Show')
            ->has('job')
            ->where('job.id', $job->id)
        );
});

test('technician can view job details', function () {
    $job = WorkshopJob::factory()->create($this->jobDefaults + [
        'customer_id' => $this->customer->id,
        'assigned_to' => $this->technician->id,
    ]);

    $response = $this->actingAs($this->technician)->get(route('jobs.show', $job));

    $response->assertStatus(200);
});

// Create Tests
test('admin can view create job page', function () {
    $response = $this->actingAs($this->admin)->get(route('jobs.create'));

    $response->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Jobs/Create')
            ->has('customers')
            ->has('technicians')
        );
});

test('technician can view create job page', function () {
    $response = $this->actingAs($this->technician)->get(route('jobs.create'));

    $response->assertStatus(200);
});

test('admin can create job', function () {
    $data = [
        'customer_id' => $this->customer->id,
        'workflow_id' => $this->workflow->id,
        'title' => 'Vehicle Repair',
        'description' => 'Needs engine check',
        'status' => JobStatus::NEW->value,
        'priority' => JobPriority::HIGH->value,
        'vehicle_registration' => 'WXY9876',
        'estimated_cost' => 1500.00,
        'due_date' => now()->addDays(7)->format('Y-m-d'),
    ];

    $response = $this->actingAs($this->admin)->post(route('jobs.store'), $data);

    $response->assertRedirect();
    $this->assertDatabaseHas('workshop_jobs', [
        'customer_id' => $this->customer->id,
        'title' => 'Vehicle Repair',
        'priority' => JobPriority::HIGH->value,
    ]);
});

test('technician can create job', function () {
    $data = [
        'customer_id' => $this->customer->id,
        'workflow_id' => $this->workflow->id,
        'title' => 'Brake Replacement',
        'description' => 'Replace brake pads',
        'status' => JobStatus::NEW->value,
    ];

    $response = $this->actingAs($this->technician)->post(route('jobs.store'), $data);

    $response->assertRedirect();
    $this->assertDatabaseHas('workshop_jobs', [
        'title' => 'Brake Replacement',
    ]);
});

test('job creation requires valid customer', function () {
    $data = [
        'customer_id' => 999999,
        'workflow_id' => $this->workflow->id,
        'title' => 'Test Job',
        'description' => 'Test Description',
        'status' => JobStatus::NEW->value,
    ];

    $response = $this->actingAs($this->admin)->post(route('jobs.store'), $data);

    $response->assertSessionHasErrors('customer_id');
});

test('job creation validates required fields', function () {
    $response = $this->actingAs($this->admin)->post(route('jobs.store'), []);

    $response->assertSessionHasErrors(['workflow_id', 'description', 'status']);
});

test('job creation validates due date is not in past', function () {
    $data = [
        'customer_id' => $this->customer->id,
        'workflow_id' => $this->workflow->id,
        'title' => 'Test Job',
        'description' => 'Test',
        'status' => JobStatus::NEW->value,
        'due_date' => now()->subDays(1)->format('Y-m-d'),
    ];

    $response = $this->actingAs($this->admin)->post(route('jobs.store'), $data);

    $response->assertSessionHasErrors('due_date');
});

// Edit Tests
test('admin can view edit job page', function () {
    $job = WorkshopJob::factory()->create($this->jobDefaults + ['customer_id' => $this->customer->id]);

    $response = $this->actingAs($this->admin)->get(route('jobs.edit', $job));

    $response->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Jobs/Edit')
            ->has('job')
            ->has('customers')
            ->has('technicians')
        );
});

test('assigned technician can view edit job page', function () {
    $job = WorkshopJob::factory()->create($this->jobDefaults + [
        'customer_id' => $this->customer->id,
        'assigned_to' => $this->technician->id,
    ]);

    $response = $this->actingAs($this->technician)->get(route('jobs.edit', $job));

    $response->assertStatus(200);
});

test('unassigned technician cannot view edit job page', function () {
    $job = WorkshopJob::factory()->create($this->jobDefaults + ['customer_id' => $this->customer->id]);

    $response = $this->actingAs($this->technician)->get(route('jobs.edit', $job));

    $response->assertStatus(403);
});

// Update Tests
test('admin can update job', function () {
    $job = WorkshopJob::factory()->create($this->jobDefaults + ['customer_id' => $this->customer->id]);

    $data = [
        'title' => 'Updated Title',
        'description' => 'Updated Description',
        'priority' => JobPriority::URGENT->value,
    ];

    $response = $this->actingAs($this->admin)->put(route('jobs.update', $job), $data);

    $response->assertRedirect();
    $this->assertDatabaseHas('workshop_jobs', [
        'id' => $job->id,
        'title' => 'Updated Title',
        'priority' => JobPriority::URGENT->value,
    ]);
});

test('assigned technician can update job', function () {
    $job = WorkshopJob::factory()->create($this->jobDefaults + [
        'customer_id' => $this->customer->id,
        'assigned_to' => $this->technician->id,
    ]);

    $data = [
        'description' => 'Updated by technician',
    ];

    $response = $this->actingAs($this->technician)->put(route('jobs.update', $job), $data);

    $response->assertRedirect();
    $this->assertDatabaseHas('workshop_jobs', [
        'id' => $job->id,
        'description' => 'Updated by technician',
    ]);
});

test('unassigned technician cannot update job', function () {
    $job = WorkshopJob::factory()->create($this->jobDefaults + ['customer_id' => $this->customer->id]);

    $response = $this->actingAs($this->technician)->put(route('jobs.update', $job), ['title' => 'Test']);

    $response->assertStatus(403);
});

// Delete Tests
test('admin can delete job', function () {
    $job = WorkshopJob::factory()->create($this->jobDefaults + ['customer_id' => $this->customer->id]);

    $response = $this->actingAs($this->admin)->delete(route('jobs.destroy', $job));

    $response->assertRedirect();
    $this->assertSoftDeleted('workshop_jobs', ['id' => $job->id]);
});

test('technician cannot delete job', function () {
    $job = WorkshopJob::factory()->create($this->jobDefaults + [
        'customer_id' => $this->customer->id,
        'assigned_to' => $this->technician->id,
    ]);

    $response = $this->actingAs($this->technician)->delete(route('jobs.destroy', $job));

    $response->assertStatus(403);
});

// Status Update Tests
test('admin can update job status', function () {
    $job = WorkshopJob::factory()->create($this->jobDefaults + [
        'customer_id' => $this->customer->id,
        'status' => JobStatus::NEW,
    ]);

    $job->statusHistories()->create([
        'user_id' => $this->admin->id,
        'from_status' => null,
        'to_status' => JobStatus::NEW,
        'changed_at' => $job->created_at,
    ]);

    $data = [
        'status' => JobStatus::IN_PROGRESS->value,
        'notes' => 'Starting work',
    ];

    $response = $this->actingAs($this->admin)->patch(route('jobs.update-status', $job), $data);

    $response->assertRedirect();
    $this->assertDatabaseHas('workshop_jobs', [
        'id' => $job->id,
        'status' => JobStatus::IN_PROGRESS->value,
    ]);
});

test('assigned technician can update job status', function () {
    $job = WorkshopJob::factory()->create($this->jobDefaults + [
        'customer_id' => $this->customer->id,
        'assigned_to' => $this->technician->id,
        'status' => JobStatus::NEW,
    ]);

    $job->statusHistories()->create([
        'user_id' => $this->technician->id,
        'from_status' => null,
        'to_status' => JobStatus::NEW,
        'changed_at' => $job->created_at,
    ]);

    $data = [
        'status' => JobStatus::IN_PROGRESS->value,
    ];

    $response = $this->actingAs($this->technician)->patch(route('jobs.update-status', $job), $data);

    $response->assertRedirect();
});

test('cannot update to invalid status transition', function () {
    $job = WorkshopJob::factory()->create($this->jobDefaults + [
        'customer_id' => $this->customer->id,
        'status' => JobStatus::NEW,
    ]);

    $data = [
        'status' => JobStatus::INVOICED->value,
    ];

    $response = $this->actingAs($this->admin)->patch(route('jobs.update-status', $job), $data);

    $response->assertSessionHasErrors('status');
});

// Timeline Tests
test('admin can view job timeline', function () {
    $job = WorkshopJob::factory()->create($this->jobDefaults + ['customer_id' => $this->customer->id]);

    $response = $this->actingAs($this->admin)->get(route('jobs.timeline', $job));

    $response->assertStatus(200)
        ->assertJsonStructure([
            'timeline' => [
                '*' => ['type', 'timestamp', 'data'],
            ],
        ]);
});

test('job timeline includes status changes', function () {
    $job = WorkshopJob::factory()->create($this->jobDefaults + [
        'customer_id' => $this->customer->id,
        'status' => JobStatus::NEW,
    ]);

    $job->statusHistories()->create([
        'user_id' => $this->admin->id,
        'from_status' => null,
        'to_status' => JobStatus::NEW,
        'changed_at' => $job->created_at,
    ]);

    $response = $this->actingAs($this->admin)->get(route('jobs.timeline', $job));

    $response->assertStatus(200)
        ->assertJsonPath('timeline.0.type', 'status_change');
});
