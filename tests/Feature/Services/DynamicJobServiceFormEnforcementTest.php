<?php

namespace Tests\Feature\Services;

use App\Models\Template\JobTemplate;
use App\Models\Template\TemplateField;
use App\Models\Template\TemplateFieldType;
use App\Models\User;
use App\Models\Workflow\Workflow;
use App\Models\Workflow\WorkflowStatus;
use App\Models\WorkshopJob;
use App\Services\Job\DynamicJobService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DynamicJobServiceFormEnforcementTest extends TestCase
{
    use RefreshDatabase;

    protected DynamicJobService $service;
    protected User $admin;
    protected Workflow $workflow;
    protected WorkflowStatus $status1;
    protected WorkflowStatus $status2;
    protected JobTemplate $requiredTemplate;
    protected TemplateField $requiredField;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->service = app(DynamicJobService::class);
        $this->admin = User::factory()->create(['role' => 'pentadbiran']);

        $adminRole = Role::firstOrCreate(['name' => 'pentadbiran', 'guard_name' => 'web'], ['is_active' => true]);
        $this->admin->syncRoles([$adminRole->name]);

        $this->workflow = Workflow::create([
            'name' => 'Test Workflow',
            'code' => 'test-workflow-' . uniqid(),
            'is_active' => true,
            'is_default' => false,
            'allowed_roles' => [$adminRole->id],
        ]);

        $this->status1 = WorkflowStatus::create([
            'workflow_id' => $this->workflow->id,
            'name' => 'Status 1',
            'code' => 'new',
            'is_initial' => true,
            'display_order' => 0,
        ]);

        $this->status2 = WorkflowStatus::create([
            'workflow_id' => $this->workflow->id,
            'name' => 'Status 2',
            'code' => 'in_progress',
            'is_initial' => false,
            'display_order' => 1,
        ]);

        $this->workflow->transitions()->create([
            'from_status_id' => $this->status1->id,
            'to_status_id' => $this->status2->id,
            'name' => 'Test Transition',
            'allowed_roles' => [$adminRole->id],
            'is_active' => true,
        ]);
        
        // Create required template with field
        $fieldType = TemplateFieldType::firstOrCreate(
            ['code' => 'text'],
            ['name' => 'Text', 'component_name' => 'TextInput']
        );
        
        $this->requiredTemplate = JobTemplate::create([
            'name' => 'Required Form',
            'code' => 'required_form',
        ]);
        
        $this->requiredField = TemplateField::create([
            'template_id' => $this->requiredTemplate->id,
            'field_type_id' => $fieldType->id,
            'name' => 'Required Field',
            'code' => 'required_field',
            'is_required' => true,
            'display_order' => 0,
        ]);
    }

    public function test_transition_succeeds_when_no_form_required(): void
    {
        $this->actingAs($this->admin);
        
        // Status 1 has NO required form
        $this->assertNull($this->status1->required_template_id);
        
        $job = WorkshopJob::factory()->create([
            'workflow_id' => $this->workflow->id,
            'current_workflow_status_id' => $this->status1->id,
            'template_id' => $this->requiredTemplate->id,
            'status' => \App\Enums\JobStatus::NEW->value,
        ]);

        $transition = $this->workflow->transitions()->first();

        // Should succeed without form data
        $result = $this->service->executeTransition($job, $transition->id, [
            'notes' => 'Test transition',
        ]);

        $this->assertNotNull($result);
        $this->assertEquals($this->status2->id, $result->current_workflow_status_id);
    }

    public function test_transition_fails_when_required_form_incomplete(): void
    {
        $this->actingAs($this->admin);
        
        // Attach required form to status 1
        $this->status1->update(['required_template_id' => $this->requiredTemplate->id]);
        
        $job = WorkshopJob::factory()->create([
            'workflow_id' => $this->workflow->id,
            'current_workflow_status_id' => $this->status1->id,
            'template_id' => $this->requiredTemplate->id,
            'status' => \App\Enums\JobStatus::NEW->value,
        ]);

        $transition = $this->workflow->transitions()->first();

        // Attempt transition without filling required field
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('incomplete');

        $this->service->executeTransition($job, $transition->id, [
            'notes' => 'Test transition',
            'field_data' => [], // Empty form data
        ]);
    }

    public function test_transition_succeeds_when_required_form_complete(): void
    {
        $this->actingAs($this->admin);
        
        // Attach required form to status 1
        $this->status1->update(['required_template_id' => $this->requiredTemplate->id]);
        
        $job = WorkshopJob::factory()->create([
            'workflow_id' => $this->workflow->id,
            'current_workflow_status_id' => $this->status1->id,
            'template_id' => $this->requiredTemplate->id,
            'status' => \App\Enums\JobStatus::NEW->value,
        ]);

        $transition = $this->workflow->transitions()->first();

        // Attempt transition WITH complete form data
        $result = $this->service->executeTransition($job, $transition->id, [
            'notes' => 'Test transition',
            'field_data' => [
                'required_field' => 'Test Value',
            ],
        ]);

        $this->assertNotNull($result);
        $this->assertEquals($this->status2->id, $result->current_workflow_status_id);
        
        // Verify field data was saved
        $fieldValues = $job->fresh()->getAllFieldValues();
        $this->assertEquals('Test Value', $fieldValues['required_field']);
    }

    public function test_transition_uses_existing_field_values(): void
    {
        $this->actingAs($this->admin);
        
        // Attach required form to status 1
        $this->status1->update(['required_template_id' => $this->requiredTemplate->id]);
        
        $job = WorkshopJob::factory()->create([
            'workflow_id' => $this->workflow->id,
            'current_workflow_status_id' => $this->status1->id,
            'template_id' => $this->requiredTemplate->id,
            'status' => \App\Enums\JobStatus::NEW->value,
        ]);

        // Pre-fill the required field
        $job->setFieldValue('required_field', 'Pre-filled Value');

        $transition = $this->workflow->transitions()->first();

        // Transition should succeed using existing value
        $result = $this->service->executeTransition($job, $transition->id, [
            'notes' => 'Test transition',
        ]);

        $this->assertNotNull($result);
        $this->assertEquals($this->status2->id, $result->current_workflow_status_id);
    }
}
