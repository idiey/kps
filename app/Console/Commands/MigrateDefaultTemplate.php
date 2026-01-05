<?php

namespace App\Console\Commands;

use App\Models\Template\JobTemplate;
use App\Models\Template\TemplateField;
use App\Models\Template\TemplateFieldType;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MigrateDefaultTemplate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'workflow:migrate-template
                            {--force : Force migration without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create default job template from workshop_jobs table schema';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if (!$this->option('force')) {
            if (!$this->confirm('This will create a default template from workshop_jobs schema. Continue?')) {
                $this->info('Migration cancelled.');
                return Command::SUCCESS;
            }
        }

        $this->info('Creating default job template...');

        // Step 1: Create template
        $template = $this->createTemplate();

        // Step 2: Create fields from workshop_jobs columns
        $this->info('Creating template fields from workshop_jobs schema...');
        $this->createFields($template);

        $this->info('✓ Default template created successfully!');
        $this->info("  Template ID: {$template->id}");

        return Command::SUCCESS;
    }

    /**
     * Create the default job template.
     */
    protected function createTemplate(): JobTemplate
    {
        $template = JobTemplate::updateOrCreate(
            ['code' => 'standard-job'],
            [
                'name' => 'Standard Workshop Job',
                'description' => 'Default template for workshop jobs. Migrated from workshop_jobs table schema.',
                'icon' => 'wrench-screwdriver',
                'color' => 'blue',
                'is_active' => true,
                'is_default' => true,
                'metadata' => [
                    'migrated_from' => 'workshop_jobs schema',
                    'migrated_at' => now()->toIso8601String(),
                ],
            ]
        );

        $this->line("  ✓ Created template: {$template->name}");

        return $template;
    }

    /**
     * Create template fields from existing workshop_jobs columns.
     */
    protected function createFields(JobTemplate $template): void
    {
        $fields = [
            // General Information Section
            [
                'section' => 'General Information',
                'display_order' => 0,
                'field_type' => 'text',
                'name' => 'Job Title',
                'code' => 'title',
                'description' => 'Brief description of the job',
                'placeholder' => 'e.g., Engine repair for Honda Civic',
                'is_required' => true,
                'grid_column_span' => 12,
            ],
            [
                'section' => 'General Information',
                'display_order' => 1,
                'field_type' => 'textarea',
                'name' => 'Job Description',
                'code' => 'description',
                'description' => 'Detailed description of the work required',
                'placeholder' => 'Describe the issue and required repairs...',
                'is_required' => false,
                'grid_column_span' => 12,
                'validation_rules' => ['max' => 1000],
            ],

            // Vehicle/Asset Information Section
            [
                'section' => 'Vehicle Information',
                'display_order' => 0,
                'field_type' => 'text',
                'name' => 'Vehicle Registration',
                'code' => 'vehicle_registration',
                'description' => 'Vehicle registration number',
                'placeholder' => 'e.g., WXY 1234',
                'is_required' => false,
                'grid_column_span' => 6,
            ],
            [
                'section' => 'Vehicle Information',
                'display_order' => 1,
                'field_type' => 'text',
                'name' => 'Asset Tag',
                'code' => 'asset_tag',
                'description' => 'Government asset identification number',
                'placeholder' => 'e.g., ASSET-2024-001',
                'is_required' => false,
                'grid_column_span' => 6,
            ],
            [
                'section' => 'Vehicle Information',
                'display_order' => 2,
                'field_type' => 'text',
                'name' => 'Make',
                'code' => 'make',
                'description' => 'Vehicle manufacturer',
                'placeholder' => 'e.g., Honda',
                'is_required' => false,
                'grid_column_span' => 6,
            ],
            [
                'section' => 'Vehicle Information',
                'display_order' => 3,
                'field_type' => 'text',
                'name' => 'Model',
                'code' => 'model',
                'description' => 'Vehicle model',
                'placeholder' => 'e.g., Civic',
                'is_required' => false,
                'grid_column_span' => 6,
            ],
            [
                'section' => 'Vehicle Information',
                'display_order' => 4,
                'field_type' => 'number',
                'name' => 'Year',
                'code' => 'year',
                'description' => 'Vehicle manufacturing year',
                'placeholder' => 'e.g., 2020',
                'is_required' => false,
                'grid_column_span' => 4,
                'validation_rules' => ['min' => 1900, 'max' => date('Y') + 1],
            ],
            [
                'section' => 'Vehicle Information',
                'display_order' => 5,
                'field_type' => 'number',
                'name' => 'Odometer (KM)',
                'code' => 'odometer_reading',
                'description' => 'Current odometer reading in kilometers',
                'placeholder' => 'e.g., 50000',
                'is_required' => false,
                'grid_column_span' => 8,
                'validation_rules' => ['min' => 0],
            ],

            // Cost Estimation Section
            [
                'section' => 'Cost Estimation',
                'display_order' => 0,
                'field_type' => 'number',
                'name' => 'Estimated Labor Cost (RM)',
                'code' => 'estimated_labor_cost',
                'description' => 'Estimated cost for labor',
                'placeholder' => '0.00',
                'is_required' => false,
                'grid_column_span' => 6,
                'validation_rules' => ['min' => 0, 'step' => 0.01],
            ],
            [
                'section' => 'Cost Estimation',
                'display_order' => 1,
                'field_type' => 'number',
                'name' => 'Estimated Parts Cost (RM)',
                'code' => 'estimated_parts_cost',
                'description' => 'Estimated cost for parts',
                'placeholder' => '0.00',
                'is_required' => false,
                'grid_column_span' => 6,
                'validation_rules' => ['min' => 0, 'step' => 0.01],
            ],
            [
                'section' => 'Cost Estimation',
                'display_order' => 2,
                'field_type' => 'calculated',
                'name' => 'Total Estimated Cost (RM)',
                'code' => 'total_estimated_cost',
                'description' => 'Total estimated cost (labor + parts)',
                'is_required' => false,
                'grid_column_span' => 12,
                'formula' => '(estimated_labor_cost || 0) + (estimated_parts_cost || 0)',
            ],

            // Scheduling Section
            [
                'section' => 'Scheduling',
                'display_order' => 0,
                'field_type' => 'date',
                'name' => 'Scheduled Start Date',
                'code' => 'scheduled_start_date',
                'description' => 'Expected date to start the job',
                'is_required' => false,
                'grid_column_span' => 6,
            ],
            [
                'section' => 'Scheduling',
                'display_order' => 1,
                'field_type' => 'date',
                'name' => 'Scheduled Completion Date',
                'code' => 'scheduled_completion_date',
                'description' => 'Target date for job completion',
                'is_required' => false,
                'grid_column_span' => 6,
            ],

            // Additional Notes
            [
                'section' => 'Additional Information',
                'display_order' => 0,
                'field_type' => 'textarea',
                'name' => 'Special Instructions',
                'code' => 'special_instructions',
                'description' => 'Any special instructions or requirements',
                'placeholder' => 'Enter any special requirements...',
                'is_required' => false,
                'grid_column_span' => 12,
            ],
        ];

        foreach ($fields as $fieldData) {
            $fieldType = TemplateFieldType::where('code', $fieldData['field_type'])->first();

            if (!$fieldType) {
                $this->warn("  ⚠ Field type not found: {$fieldData['field_type']} for field {$fieldData['code']}");
                continue;
            }

            $field = TemplateField::updateOrCreate(
                [
                    'template_id' => $template->id,
                    'code' => $fieldData['code'],
                ],
                [
                    'field_type_id' => $fieldType->id,
                    'name' => $fieldData['name'],
                    'description' => $fieldData['description'] ?? null,
                    'placeholder' => $fieldData['placeholder'] ?? null,
                    'section' => $fieldData['section'],
                    'display_order' => $fieldData['display_order'],
                    'grid_column_span' => $fieldData['grid_column_span'] ?? 12,
                    'is_required' => $fieldData['is_required'] ?? false,
                    'validation_rules' => $fieldData['validation_rules'] ?? null,
                    'formula' => $fieldData['formula'] ?? null,
                    'is_active' => true,
                ]
            );

            $this->line("  ✓ Created field: {$field->name} ({$field->code})");
        }
    }
}
