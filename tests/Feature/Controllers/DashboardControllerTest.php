<?php

use App\Enums\JobStatus;
use App\Models\Customer;
use App\Models\User;
use App\Models\WorkshopJob;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'pentadbiran']);
    $this->technician = User::factory()->create(['role' => 'juruteknik']);
    $this->customer = Customer::factory()->create();
});

test('admin can view workload dashboard', function () {
    $response = $this->actingAs($this->admin)->get(route('dashboard.workload'));

    $response->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Dashboard/Workload')
            ->has('technicians')
            ->has('statistics')
        );
});

test('technician can view workload dashboard', function () {
    $response = $this->actingAs($this->technician)->get(route('dashboard.workload'));

    $response->assertStatus(200);
});

test('workload dashboard shows technician job counts', function () {
    $tech1 = User::factory()->create(['role' => 'juruteknik', 'name' => 'Tech One']);
    $tech2 = User::factory()->create(['role' => 'juruteknik', 'name' => 'Tech Two']);

    WorkshopJob::factory()->count(3)->create([
        'customer_id' => $this->customer->id,
        'assigned_to' => $tech1->id,
        'status' => JobStatus::IN_PROGRESS,
    ]);

    WorkshopJob::factory()->count(2)->create([
        'customer_id' => $this->customer->id,
        'assigned_to' => $tech2->id,
        'status' => JobStatus::IN_PROGRESS,
    ]);

    $response = $this->actingAs($this->admin)->get(route('dashboard.workload'));

    $response->assertInertia(function (Assert $page) use ($tech1, $tech2) {
        $technicians = $page->toArray()['props']['technicians'];

        $tech1Data = collect($technicians)->firstWhere('id', $tech1->id);
        $tech2Data = collect($technicians)->firstWhere('id', $tech2->id);

        expect($tech1Data['active_jobs_count'])->toBe(3);
        expect($tech2Data['active_jobs_count'])->toBe(2);

        return true;
    });
});

test('workload dashboard shows overall statistics', function () {
    WorkshopJob::factory()->create([
        'customer_id' => $this->customer->id,
        'status' => JobStatus::NEW,
    ]);

    WorkshopJob::factory()->count(2)->create([
        'customer_id' => $this->customer->id,
        'status' => JobStatus::IN_PROGRESS,
    ]);

    WorkshopJob::factory()->create([
        'customer_id' => $this->customer->id,
        'status' => JobStatus::COMPLETED,
    ]);

    $response = $this->actingAs($this->admin)->get(route('dashboard.workload'));

    $response->assertInertia(function (Assert $page) {
        $stats = $page->toArray()['props']['statistics'];

        expect($stats['total_jobs'])->toBe(4);
        expect($stats['new_jobs'])->toBe(1);
        expect($stats['in_progress_jobs'])->toBe(2);
        expect($stats['completed_jobs'])->toBe(1);

        return true;
    });
});

test('workload dashboard shows overdue jobs', function () {
    WorkshopJob::factory()->create([
        'customer_id' => $this->customer->id,
        'status' => JobStatus::IN_PROGRESS,
        'due_date' => now()->subDays(2),
    ]);

    WorkshopJob::factory()->create([
        'customer_id' => $this->customer->id,
        'status' => JobStatus::IN_PROGRESS,
        'due_date' => now()->addDays(2),
    ]);

    $response = $this->actingAs($this->admin)->get(route('dashboard.workload'));

    $response->assertInertia(function (Assert $page) {
        $stats = $page->toArray()['props']['statistics'];

        expect($stats['overdue_jobs'])->toBe(1);

        return true;
    });
});

test('technician workload dashboard shows only their jobs', function () {
    WorkshopJob::factory()->count(2)->create([
        'customer_id' => $this->customer->id,
        'assigned_to' => $this->technician->id,
    ]);

    WorkshopJob::factory()->create([
        'customer_id' => $this->customer->id,
        'assigned_to' => null,
    ]);

    $response = $this->actingAs($this->technician)->get(route('dashboard.my-jobs'));

    $response->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Dashboard/MyJobs')
            ->has('jobs', 2)
        );
});
