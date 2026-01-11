<?php

namespace Database\Seeders;

use App\Models\Job\JobFieldValue;
use App\Models\Template\TemplateField;
use App\Models\WorkshopJob;
use Illuminate\Database\Seeder;

class JobFieldValuesSeeder extends Seeder
{
    /**
     * Seed field values for Job 19 to test workflow templates display.
     */
    public function run(): void
    {
        $jobId = 19;
        
        // Get the job
        $job = WorkshopJob::find($jobId);
        
        if (!$job) {
            $this->command->error("Job {$jobId} not found!");
            return;
        }
        
        $this->command->info("Seeding field values for Job {$jobId}: {$job->job_number}");
        
        // Get all workflow statuses with required templates
        $statusesWithTemplates = $job->workflow->statuses()
            ->whereNotNull('required_template_id')
            ->with('requiredTemplate.fields.fieldType')
            ->get();
        
        if ($statusesWithTemplates->isEmpty()) {
            $this->command->warn("No workflow statuses with templates found for this job's workflow");
            return;
        }
        
        $totalFieldsSeeded = 0;
        
        foreach ($statusesWithTemplates as $status) {
            $template = $status->requiredTemplate;
            $this->command->info("  Status: {$status->name}");
            $this->command->info("  Template: {$template->name}");
            
            $fields = $template->fields;
            
            foreach ($fields as $field) {
                // Generate sample data based on field type
                $value = $this->generateSampleValue($field);
                
                // Create or update field value
                JobFieldValue::updateOrCreate(
                    [
                        'job_id' => $jobId,
                        'field_id' => $field->id,
                    ],
                    $this->getValueColumns($field, $value)
                );
                
                $this->command->line("    ✓ {$field->code}: {$value}");
                $totalFieldsSeeded++;
            }
            
            $this->command->newLine();
        }
        
        $this->command->info("✅ Total fields seeded: {$totalFieldsSeeded}");
    }
    
    /**
     * Generate sample value based on field type
     */
    private function generateSampleValue(TemplateField $field): mixed
    {
        $fieldType = $field->fieldType->code;
        
        return match($fieldType) {
            'text' => 'Sample ' . $field->name,
            'textarea' => 'This is a sample text for ' . $field->name . '. Lorem ipsum dolor sit amet.',
            'number' => rand(1, 100),
            'calculated' => rand(50, 200) / 10,
            'date' => now()->format('Y-m-d'),
            'datetime' => now()->format('Y-m-d H:i:s'),
            'checkbox' => (bool) rand(0, 1),
            'dropdown' => $this->getRandomOption($field),
            'radio' => $this->getRandomOption($field),
            'multiselect' => $this->getRandomOptions($field),
            default => 'Sample Data',
        };
    }
    
    /**
     * Get random option from field options
     */
    private function getRandomOption(TemplateField $field): ?string
    {
        if (!$field->options || !is_array($field->options) || empty($field->options)) {
            return null;
        }
        
        return $field->options[array_rand($field->options)];
    }
    
    /**
     * Get random multiple options from field options
     */
    private function getRandomOptions(TemplateField $field): ?array
    {
        if (!$field->options || !is_array($field->options) || empty($field->options)) {
            return null;
        }
        
        $count = min(2, count($field->options));
        $keys = array_rand($field->options, $count);
        
        if (!is_array($keys)) {
            $keys = [$keys];
        }
        
        return array_map(fn($key) => $field->options[$key], $keys);
    }
    
    /**
     * Get value columns based on field type
     */
    private function getValueColumns(TemplateField $field, mixed $value): array
    {
        $fieldType = $field->fieldType->code;
        
        return match($fieldType) {
            'text', 'textarea', 'dropdown', 'radio' => ['value_text' => $value],
            'number', 'calculated' => ['value_number' => $value],
            'date' => ['value_date' => $value],
            'datetime' => ['value_datetime' => $value],
            'checkbox' => ['value_boolean' => $value],
            'multiselect', 'file', 'image' => ['value_json' => $value],
            default => ['value_text' => $value],
        };
    }
}
