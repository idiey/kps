<?php

use App\Models\Customer;
use App\Models\Template\JobTemplate;
use App\Models\User;
use App\Models\Workflow\Workflow;
use App\Models\Workflow\WorkflowStatus;
use App\Models\Workflow\WorkflowTransition;
use App\Models\WorkshopJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create users with simple role strings (not requiring seeder)
    $this->admin = User::factory()->create([
        'name' => 'Admin User',
        'email' => 'admin@test.com',
        'role' => 'pentadbiran',
    ]);
    
    $this->technician = User::factory()->create([
        'name' => 'Technician User',  
        'email' => 'tech@test.com',
        'role' => 'juruteknik',
    ]);
    
    $this->customer = Customer::factory()->create();
    
    // Seed minimal required data
    $this->seed([
        \Database\Seeders\TemplateFieldTypeSeeder::class,
        \Database\Seeders\SimpleKewPA10WorkflowSeeder::class,
    ]);
    
    $this->template = JobTemplate::where('code', 'kewpa10_simple')->first();
    $this->workflow = Workflow::where('code', 'kewpa10_simple_flow')->first();
    
    // Ensure template and workflow exist
    if (!$this->template || !$this->workflow) {
        throw new \Exception('Test setup failed: template or workflow not found');
    }
});

// Template Selection Tests
test('admin can view template selection page', function () {
    $response = $this->actingAs($this->admin)->get(route('jobs.create'));

    $response->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Jobs/SelectTemplate')
            ->has('templates')
        );
});

test('admin can view job creation with template', function () {
    $response = $this->actingAs($this->admin)
        ->get(route('jobs.create', ['template' => $this->template->id]));

    $response->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Jobs/Create')
            ->has('template')
            ->has('workflows')
            ->has('formSchema')
        );
});

test('job creation page auto-selects default workflow', function () {
    $response = $this->actingAs($this->admin)
        ->get(route('jobs.create', ['template' => $this->template->id]));

    $response->assertInertia(fn (Assert $page) => $page
        ->where('selectedWorkflow.is_default', true)
    );
});

// Job Creation with Dynamic Fields Tests
test('admin can create job with workflow', function () {
    $data = [
        'customer_id' => $this->customer->id,
        'title' => 'Test Workflow Job',
        'description' => 'Testing workflow integration',
        'status' => \App\Enums\JobStatus::NEW->value,
        'workflow_id' => $this->workflow->id,
    ];

    $response = $this->actingAs($this->admin)
        ->post(route('jobs.store'), $data);

    $response->assertRedirect();
    $this->assertDatabaseHas('workshop_jobs', [
        'customer_id' => $this->customer->id,
        'workflow_id' => $this->workflow->id,
    ]);
});

test('job creation validates template exists', function () {
    $data = [
        'customer_id' => $this->customer->id,
        'template_id' => 999999,
        'workflow_id' => $this->workflow->id,
        'description' => 'Test',
        'status' => \App\Enums\JobStatus::NEW->value,
    ];

    $response = $this->actingAs($this->admin)
        ->post(route('jobs.store'), $data);

    $response->assertSessionHasErrors('template_id');
});

test('job creation validates workflow exists', function () {
    $data = [
        'customer_id' => $this->customer->id,
        'template_id' => $this->template->id,
        'workflow_id' => 999999,
        'description' => 'Test',
        'status' => \App\Enums\JobStatus::NEW->value,
    ];

    $response = $this->actingAs($this->admin)
        ->post(route('jobs.store'), $data);

    $response->assertSessionHasErrors('workflow_id');
});

// Note: JobController doesn't validate workflow-template association
// That validation happens in DynamicJobController when using template-based creation

test('job creation requires description and status', function () {
    $data = [
        'customer_id' => $this->customer->id,
        'title' => 'Test Job',
        'workflow_id' => $this->workflow->id,
    ];

    $response = $this->actingAs($this->admin)
        ->post(route('jobs.store'), $data);

    $response->assertSessionHasErrors(['description', 'status']);
});

// Workflow Transition Tests (D4 & D5)
test('job show page displays available transitions', function () {
    $job = WorkshopJob::factory()->create([
        'template_id' => $this->template->id,
        'workflow_id' => $this->workflow->id,
    ]);

    $response = $this->actingAs($this->admin)
        ->get(route('jobs.show', $job));

    $response->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Jobs/Show')
            ->has('job')
            ->has('availableTransitions')
        );
});

test('admin can execute workflow transition without form', function () {
    $job = WorkshopJob::factory()->create([
        'template_id' => $this->template->id,
        'workflow_id' => $this->workflow->id,
    ]);

    // Get first available transition
    $transition = $job->workflow->transitions()
        ->where('from_status_id', $job->current_workflow_status_id)
        ->first();

    $data = [
        'notes' => 'Transition executed',
        'field_data' => [],
        'metadata' => [],
    ];

    $response = $this->actingAs($this->admin)
        ->post("/jobs/{$job->id}/transitions/{$transition->id}", $data);

    $response->assertRedirect();
    $this->assertDatabaseHas('workshop_jobs', [
        'id' => $job->id,
        'current_workflow_status_id' => $transition->to_status_id,
    ]);
});

test('transition blocks when required form is missing', function () {
    $job = WorkshopJob::factory()->create([
        'template_id' => $this->template->id,
        'workflow_id' => $this->workflow->id,
    ]);

    // Find the transition and set its target status to require a template
    $transition = $job->workflow->transitions()
        ->where('from_status_id', $job->current_workflow_status_id)
        ->first();
    
    $toStatus = WorkflowStatus::find($transition->to_status_id);
    $toStatus->update(['required_template_id' => $this->template->id]);

    $data = [
        'notes' => 'Attempting transition',
        'field_data' => [], // Empty form data
        'metadata' => [],
    ];

    $response = $this->actingAs($this->admin)
        ->post("/jobs/{$job->id}/transitions/{$transition->id}", $data);

    $response->assertSessionHasErrors();
});

test('transition succeeds when required form data is provided', function () {
    $job = WorkshopJob::factory()->create([
        'template_id' => $this->template->id,
        'workflow_id' => $this->workflow->id,
    ]);

    $transition = $job->workflow->transitions()
        ->where('from_status_id', $job->current_workflow_status_id)
        ->first();
    
    $toStatus = WorkflowStatus::find($transition->to_status_id);
    $toStatus->update(['required_template_id' => $this->template->id]);

    $data = [
        'notes' => 'Transition with form',
        'field_data' => [
            'asset_description' => 'Completed Asset',
            'department' => 'Department A',
        ],
        'metadata' => [],
    ];

    $response = $this->actingAs($this->admin)
        ->post("/jobs/{$job->id}/transitions/{$transition->id}", $data);

    $response->assertRedirect();
    
    // Verify transition succeeded
    $this->assertDatabaseHas('workshop_jobs', [
        'id' => $job->id,
        'current_workflow_status_id' => $transition->to_status_id,
    ]);
    
    // Verify form data was stored
    $this->assertDatabaseHas('job_field_values', [
        'job_id' => $job->id,
    ]);
});

test('form data persists across multiple transitions', function () {
    $job = WorkshopJob::factory()->create([
        'template_id' => $this->template->id,
        'workflow_id' => $this->workflow->id,
    ]);

    // Execute first transition with form data
    $transition1 = $job->workflow->transitions()
        ->where('from_status_id', $job->current_workflow_status_id)
        ->first();

    $formData1 = [
        'notes' => 'First transition',
        'field_data' => [
            'asset_description' => 'Initial Description',
        ],
        'metadata' => [],
    ];

    $this->actingAs($this->admin)
        ->post(route('jobs.transition', ['job' => $job, 'transition' => $transition1]), $formData1);

    $job->refresh();

    // Execute second transition with different form data
    $transition2 = $job->workflow->transitions()
        ->where('from_status_id', $job->current_workflow_status_id)
        ->first();

    if ($transition2) {
        $formData2 = [
            'notes' => 'Second transition',
            'field_data' => [
                'department' => 'Updated Department',
            ],
            'metadata' => [],
        ];

        $this->actingAs($this->admin)
            ->post(route('jobs.transition', ['job' => $job, 'transition' => $transition2]), $formData2);

        // Verify both form data entries exist
        $this->assertDatabaseCount('job_field_values', 2);
    }
});

// API Endpoint Tests
test('can get available transitions for job', function () {
    $job = WorkshopJob::factory()->create([
        'template_id' => $this->template->id,
        'workflow_id' => $this->workflow->id,
    ]);

    $response = $this->actingAs($this->admin)
        ->get("/api/jobs/{$job->id}/available-transitions");

    $response->assertStatus(200)
        ->assertJsonStructure([
            'transitions',
            'current_status',
        ]);
});

test('can get form schema for template', function () {
    $response = $this->actingAs($this->admin)
        ->get("/api/templates/{$this->template->id}/schema");

    $response->assertStatus(200)
        ->assertJsonStructure([
            'fields',
        ]);
});

test('can get workflows for template', function () {
    $response = $this->actingAs($this->admin)
        ->get("/api/templates/{$this->template->id}/workflows");

    $response->assertStatus(200)
        ->assertJsonStructure([
            '*' => ['id', 'name', 'description'],
        ]);
});

test('can validate field data against template', function () {
    $data = [
        'field_data' => [
            'asset_description' => 'Test Asset',
            'department' => 'Engineering',
        ],
    ];

    $response = $this->actingAs($this->admin)
        ->post("/api/templates/{$this->template->id}/validate", $data);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'valid',
            'errors',
        ]);
});

test('unauthorized user cannot execute transition', function () {
    $job = WorkshopJob::factory()->create([
        'template_id' => $this->template->id,
        'workflow_id' => $this->workflow->id,
    ]);

    $transition = $job->workflow->transitions()
        ->where('from_status_id', $job->current_workflow_status_id)
        ->first();

    $response = $this->actingAs($this->technician)
        ->post("/jobs/{$job->id}/transitions/{$transition->id}", [
            'notes' => 'Unauthorized attempt',
        ]);

    $response->assertStatus(403);
});
