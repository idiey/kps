<?php

namespace App\Console\Commands;

use App\Enums\JobStatus;
use App\Models\Customer;
use App\Models\Template\JobTemplate;
use App\Models\Template\TemplateField;
use App\Models\Template\TemplateFieldType;
use App\Models\User;
use App\Models\Workflow\Workflow;
use App\Models\Workflow\WorkflowStatus;
use App\Services\Job\DynamicJobService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class SeedRequiredFormJob extends Command
{
    protected $signature = 'workshop:seed-required-form-job {--code= : Fixed workflow code for repeatability}';

    protected $description = 'Seeds a minimal workflow + job that requires a form before transitioning';

    public function handle(DynamicJobService $service): int
    {
        $admin = User::firstOrCreate(
            ['email' => 'seed-admin@example.com'],
            ['name' => 'Seed Admin', 'password' => bcrypt('password'), 'role' => 'pentadbiran']
        );
        $admin->forceFill(['role' => 'pentadbiran'])->save();

        $adminRole = Role::firstOrCreate(['name' => 'pentadbiran', 'guard_name' => 'web'], ['is_active' => true]);
        $admin->syncRoles([$adminRole->name]);

        Auth::guard('web')->login($admin);

        $template = JobTemplate::create([
            'name' => 'Required Form (Seed)',
            'code' => 'required-form-seed-' . uniqid(),
            'is_active' => true,
        ]);

        $fieldType = TemplateFieldType::firstOrCreate(
            ['code' => 'text'],
            ['name' => 'Text', 'component_name' => 'BaseInput', 'is_active' => true]
        );

        TemplateField::create([
            'template_id' => $template->id,
            'field_type_id' => $fieldType->id,
            'name' => 'Required Field',
            'code' => 'required_field',
            'is_required' => true,
            'is_active' => true,
            'display_order' => 0,
        ]);

        $workflowCode = $this->option('code') ?: 'required-form-seed-flow-' . uniqid();

        $workflow = Workflow::create([
            'name' => 'Required Form Flow (Seed)',
            'code' => $workflowCode,
            'is_active' => true,
            'is_default' => false,
            'allowed_roles' => [$adminRole->id],
        ]);

        $s1 = WorkflowStatus::create([
            'workflow_id' => $workflow->id,
            'name' => 'S1',
            'code' => 's1_' . uniqid(),
            'is_initial' => true,
            'is_final' => false,
            'display_order' => 0,
            'required_template_id' => $template->id,
        ]);

        $s2 = WorkflowStatus::create([
            'workflow_id' => $workflow->id,
            'name' => 'S2',
            'code' => 's2_' . uniqid(),
            'is_initial' => false,
            'is_final' => false,
            'display_order' => 1,
        ]);

        $transition = $workflow->transitions()->create([
            'from_status_id' => $s1->id,
            'to_status_id' => $s2->id,
            'name' => 'Go S2',
            'allowed_roles' => [$adminRole->id],
            'conditions' => null,
            'actions' => null,
            'requires_comment' => false,
            'requires_permission' => null,
            'is_active' => true,
        ]);

        $customer = Customer::factory()->create();
        $job = \App\Models\WorkshopJob::create([
            'job_number' => 'WJ-REQ-' . strtoupper(substr(uniqid(), -8)),
            'customer_id' => $customer->id,
            'title' => 'Seed Job (Required Form)',
            'description' => 'Seeded for required-form transition test',
            'status' => JobStatus::NEW->value,
            'priority' => \App\Enums\JobPriority::MEDIUM->value,
            'workflow_id' => $workflow->id,
            'current_workflow_status_id' => $s1->id,
        ]);

        $this->info("Created:");
        $this->line("  user_id={$admin->id}");
        $this->line("  workflow_id={$workflow->id} code={$workflow->code}");
        $this->line("  status_from_id={$s1->id} required_template_id={$s1->required_template_id}");
        $this->line("  transition_id={$transition->id} from={$s1->id} to={$s2->id}");
        $this->line("  job_id={$job->id} job_number={$job->job_number} current_status_id={$job->current_workflow_status_id}");

        $this->newLine();
        $this->info('Attempting transition WITHOUT required_field...');
        try {
            $service->executeTransition($job->fresh(), $transition->id, ['notes' => 'Try without form']);
            $this->error('Unexpected: transition succeeded without required form data');
        } catch (\InvalidArgumentException $e) {
            $this->line('Blocked as expected: ' . $e->getMessage());
        }

        $job->refresh();
        $this->line("After blocked attempt: current_status_id={$job->current_workflow_status_id}");

        $this->newLine();
        $this->info('Attempting transition WITH required_field...');
        $service->executeTransition($job->fresh(), $transition->id, [
            'notes' => 'Try with form',
            'field_data' => ['required_field' => 'OK'],
        ]);

        $job->refresh();
        $this->line("After success: current_status_id={$job->current_workflow_status_id}");
        $this->line('Saved field value: ' . ($job->getAllFieldValues()['required_field'] ?? '(missing)'));

        return self::SUCCESS;
    }
}

