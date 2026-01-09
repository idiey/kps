<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Workflow\Workflow;
use App\Models\Workflow\WorkflowStatus;
use App\Models\Workflow\WorkflowTransition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkflowImportTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_can_import_workflow_replace_mode()
    {
        // Source Workflow
        $source = Workflow::create([
            'name' => 'Source Workflow',
            'code' => 'source_workflow',
        ]);
        $s1 = $source->statuses()->create(['name' => 'S1', 'code' => 's1', 'color' => '#000000']);
        $s2 = $source->statuses()->create(['name' => 'S2', 'code' => 's2', 'color' => '#000000']);
        $source->transitions()->create([
            'name' => 'T1',
            'from_status_id' => $s1->id,
            'to_status_id' => $s2->id,
        ]);

        // Target Workflow (with existing garbage)
        $target = Workflow::create([
            'name' => 'Target Workflow',
            'code' => 'target_workflow',
        ]);
        $t1 = $target->statuses()->create(['name' => 'Old Status', 'code' => 'old_status', 'color' => '#ffffff']);
        $target->transitions()->create([
            'name' => 'Old Trans',
            'from_status_id' => $t1->id,
            'to_status_id' => $t1->id, // Self loop
        ]);

        // Act
        $response = $this->actingAs($this->user)
            ->post(route('admin.workflows.import', $target), [
                'source_workflow_id' => $source->id,
                'mode' => 'replace',
            ]);

        $response->assertSessionHasNoErrors();
        
        // Assert
        $this->assertSoftDeleted('workflow_statuses', ['id' => $t1->id]);
        $this->assertCount(2, $target->fresh()->statuses);
        $this->assertCount(1, $target->fresh()->transitions);
        
        // Key assertion: New transition must point to NEW statuses, not source statuses
        $newTransition = $target->fresh()->transitions->first();
        $this->assertNotEquals($s1->id, $newTransition->from_status_id);
        $this->assertNotEquals($s2->id, $newTransition->to_status_id);
        $this->assertEquals($target->id, $newTransition->fromStatus->workflow_id);
    }

    public function test_can_import_workflow_append_mode()
    {
        // Source
        $source = Workflow::create([
            'name' => 'Source Append',
            'code' => 'source_append',
        ]);
        $source->statuses()->create(['name' => 'Source Status', 'code' => 'source_st', 'color' => '#000000']);

        // Target
        $target = Workflow::create([
            'name' => 'Target Append',
            'code' => 'target_append',
        ]);
        $target->statuses()->create(['name' => 'Target Status', 'code' => 'target_st', 'color' => '#000000']);

        // Act
        $response = $this->actingAs($this->user)
            ->post(route('admin.workflows.import', $target), [
                'source_workflow_id' => $source->id,
                'mode' => 'append',
            ]);

        $response->assertSessionHasNoErrors();

        // Assert
        $this->assertCount(2, $target->fresh()->statuses); // 1 old + 1 new
    }
}
