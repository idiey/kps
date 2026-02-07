<?php

use App\Enums\JobPriority;
use App\Enums\JobStatus;
use App\Models\Customer;
use App\Models\User;
use App\Models\Workflow\Workflow;
use App\Models\Workflow\WorkflowStatus;
use App\Services\Job\DynamicJobService;
use App\Services\Workflow\WorkflowExecutor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

test('alabala workflow gates transitions by role for visibility and execution', function () {
    $pentadbirRole = Role::firstOrCreate(['name' => 'pentadbiran', 'guard_name' => 'web'], ['is_active' => true]);
    $pelulusRole = Role::firstOrCreate(['name' => 'pelulus', 'guard_name' => 'web'], ['is_active' => true]);

    $workflow = Workflow::create([
        'name' => 'alabala',
        'code' => 'alabala',
        'is_active' => true,
        'is_default' => false,
        'allowed_roles' => [$pentadbirRole->id, $pelulusRole->id],
    ]);

    $start = WorkflowStatus::create([
        'workflow_id' => $workflow->id,
        'name' => 'Start',
        'code' => 'start',
        'is_initial' => true,
        'is_final' => false,
        'display_order' => 10,
    ]);

    $inprogress = WorkflowStatus::create([
        'workflow_id' => $workflow->id,
        'name' => 'In Progress',
        'code' => 'inprogress',
        'is_initial' => false,
        'is_final' => false,
        'display_order' => 20,
    ]);

    $end = WorkflowStatus::create([
        'workflow_id' => $workflow->id,
        'name' => 'End',
        'code' => 'end',
        'is_initial' => false,
        'is_final' => true,
        'display_order' => 30,
    ]);

    $t1 = $workflow->transitions()->create([
        'from_status_id' => $start->id,
        'to_status_id' => $inprogress->id,
        'name' => 'Start → In Progress',
        'allowed_roles' => [$pentadbirRole->id],
        'is_active' => true,
    ]);

    $t2 = $workflow->transitions()->create([
        'from_status_id' => $inprogress->id,
        'to_status_id' => $end->id,
        'name' => 'In Progress → End',
        'allowed_roles' => [$pelulusRole->id],
        'is_active' => true,
    ]);

    $pentadbir = User::factory()->create(['role' => 'pentadbiran']);
    $pentadbir->syncRoles([$pentadbirRole->name]);

    $pelulus = User::factory()->create(['role' => 'pelulus']);
    $pelulus->syncRoles([$pelulusRole->name]);

    $customer = Customer::factory()->create();
    $job = \App\Models\WorkshopJob::create([
        'job_number' => 'WJ-ALABALA-' . strtoupper(substr(uniqid(), -8)),
        'customer_id' => $customer->id,
        'title' => 'Alabala Job',
        'description' => 'Test role gating',
        'status' => JobStatus::NEW->value,
        'priority' => JobPriority::MEDIUM->value,
        'workflow_id' => $workflow->id,
        'current_workflow_status_id' => $start->id,
    ]);

    $executor = app(WorkflowExecutor::class);
    $service = app(DynamicJobService::class);

    $this->actingAs($pentadbir);
    expect($executor->getAvailableTransitions($job->fresh())->pluck('id')->all())->toEqual([$t1->id]);

    $this->actingAs($pelulus);
    expect($executor->getAvailableTransitions($job->fresh())->pluck('id')->all())->toEqual([]);

    $this->actingAs($pentadbir);
    $service->executeTransition($job->fresh(), $t1->id, ['notes' => 't1']);
    $job->refresh();
    expect($job->current_workflow_status_id)->toBe($inprogress->id);

    $this->actingAs($pentadbir);
    expect($executor->getAvailableTransitions($job->fresh())->pluck('id')->all())->toEqual([]);

    $this->actingAs($pelulus);
    expect($executor->getAvailableTransitions($job->fresh())->pluck('id')->all())->toEqual([$t2->id]);

    $this->actingAs($pentadbir);
    expect(fn () => $service->executeTransition($job->fresh(), $t2->id, ['notes' => 't2']))
        ->toThrow(\Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException::class);

    $this->actingAs($pelulus);
    $service->executeTransition($job->fresh(), $t2->id, ['notes' => 't2']);
    $job->refresh();
    expect($job->current_workflow_status_id)->toBe($end->id);
});
