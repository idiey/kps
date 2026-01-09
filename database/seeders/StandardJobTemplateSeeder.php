<?php

namespace Database\Seeders;

use App\Models\Template\JobTemplate;
use App\Models\Workflow\Workflow;
use Illuminate\Database\Seeder;

class StandardJobTemplateSeeder extends Seeder
{
    public function run(): void
    {
        // Get the KEW.PA-10 workflow
        $workflow = Workflow::where('code', 'kew-pa-10-external')->first();

        if (!$workflow) {
            $this->command->warn('KEW.PA-10 workflow not found. Run KewPa10WorkflowSeeder first.');
            return;
        }

        // Create the standard-job template
        $template = JobTemplate::firstOrCreate(
            ['code' => 'standard-job'],
            [
                'name' => 'Standard Workshop Job',
                'description' => 'Standard template for workshop jobs including KEW.PA-10 repair requests.',
                'icon' => 'wrench',
                'color' => '#3b82f6',
                'is_active' => true,
                'is_default' => true,
                'default_workflow_id' => $workflow->id,
            ]
        );

        $this->command->info("Created/Found template: {$template->name}");

        // Attach workflow to template (if not already attached)
        if (!$template->workflows()->where('workflows.id', $workflow->id)->exists()) {
            $template->workflows()->attach($workflow->id, ['is_default' => true]);
            $this->command->info("Attached workflow '{$workflow->name}' to template.");
        } else {
            $this->command->info("Workflow already attached to template.");
        }
    }
}
