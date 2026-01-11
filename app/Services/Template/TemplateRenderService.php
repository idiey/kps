<?php

namespace App\Services\Template;

use App\Models\Template\JobTemplate;
use App\Models\WorkshopJob;

class TemplateRenderService
{
    /**
     * Generate form schema for a template.
     *
     * @param JobTemplate $template
     * @param WorkshopJob|null $job Existing job for edit mode
     * @return array
     */
    public function generateFormSchema(JobTemplate $template, ?WorkshopJob $job = null): array
    {
        $fields = $template->fields()
            ->with('fieldType')
            ->where('is_active', true)
            ->orderBy('section')
            ->orderBy('display_order')
            ->get();

        $schema = $fields->groupBy('section')->map(function ($sectionFields, $sectionName) use ($job) {
            return [
                'name' => $sectionName ?: 'default',
                'fields' => $sectionFields->map(function ($field) use ($job) {
                    $config = $field->toFormConfig();

                    // Add current value if editing existing job
                    if ($job) {
                        $config['value'] = $job->getFieldValue($field->code);
                    }

                    return $config;
                })->values()->toArray(),
            ];
        })->values()->toArray();

        return [
            'template' => [
                'id' => $template->id,
                'name' => $template->name,
                'code' => $template->code,
                'description' => $template->description,
            ],
            'sections' => $schema,
        ];
    }

    /**
     * Validate form data against template.
     *
     * @param JobTemplate $template
     * @param array $data
     * @return array Array of errors (empty if valid)
     */
    public function validateFormData(JobTemplate $template, array $data): array
    {
        $errors = [];

        $fields = $template->fields()
            ->with('fieldType')
            ->where('is_active', true)
            ->get();

        foreach ($fields as $field) {
            $value = $data[$field->code] ?? null;

            // Check if field is visible based on conditional rules
            if (!$field->isVisible($data)) {
                continue;
            }

            // Validate field
            $fieldErrors = $field->validateValue($value);

            if (!empty($fieldErrors)) {
                $errors[$field->code] = $fieldErrors;
            }

            // Type-specific validation
            $typeErrors = $this->validateFieldType($field, $value);
            if (!empty($typeErrors)) {
                $errors[$field->code] = array_merge(
                    $errors[$field->code] ?? [],
                    $typeErrors
                );
            }
        }

        return $errors;
    }

    /**
     * Validate field value based on field type.
     */
    protected function validateFieldType($field, $value): array
    {
        $errors = [];
        $fieldType = $field->fieldType->code;

        if ($value === null || $value === '') {
            return $errors;
        }

        match ($fieldType) {
            'number' => $this->validateNumber($value, $field, $errors),
            'email' => $this->validateEmail($value, $errors),
            'date' => $this->validateDate($value, $errors),
            'file', 'image' => $this->validateFile($value, $field, $errors),
            default => null,
        };

        return $errors;
    }

    /**
     * Validate number field.
     */
    protected function validateNumber($value, $field, array &$errors): void
    {
        if (!is_numeric($value)) {
            $errors[] = "{$field->name} must be a number";
        }

        $validationRules = $field->validation_rules ?? [];

        if (isset($validationRules['min']) && $value < $validationRules['min']) {
            $errors[] = "{$field->name} must be at least {$validationRules['min']}";
        }

        if (isset($validationRules['max']) && $value > $validationRules['max']) {
            $errors[] = "{$field->name} must not exceed {$validationRules['max']}";
        }
    }

    /**
     * Validate email field.
     */
    protected function validateEmail($value, array &$errors): void
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        }
    }

    /**
     * Validate date field.
     */
    protected function validateDate($value, array &$errors): void
    {
        if (!strtotime($value)) {
            $errors[] = "Invalid date format";
        }
    }

    /**
     * Validate file field.
     */
    protected function validateFile($value, $field, array &$errors): void
    {
        // TODO: Implement file validation
        // Check file size, type, etc.
    }

    /**
     * Process form data and save to job.
     *
     * @param WorkshopJob $job
     * @param array $data
     * @param JobTemplate|null $template Optional specific template to use
     */
    public function saveFormData(WorkshopJob $job, array $data, ?JobTemplate $template = null): void
    {
        $targetTemplate = $template ?? $job->template;

        if (!$targetTemplate) {
            // For backward compatibility or if no template provided/found
            if (!empty($data)) {
                 // Try to save any matching fields we can find in the system? 
                 // Or just log a warning? For now, we'll return to avoid crashing.
                 return;
            }
            throw new \InvalidArgumentException('Job must have a template assigned or template must be provided');
        }

        $fields = $targetTemplate->fields()
            ->with('fieldType')
            ->where('is_active', true)
            ->get();

        foreach ($fields as $field) {
            if (array_key_exists($field->code, $data)) {
                $value = $data[$field->code];

                // Handle calculated fields (skip saving user input)
                if ($field->fieldType->code === 'calculated' && $field->formula) {
                    $value = $field->evaluateFormula($data);
                }

                // Save field value
                $job->setFieldValue($field->code, $value);
            }
        }
    }

    /**
     * Get template with workflows for selection.
     *
     * @param JobTemplate $template
     * @return array
     */
    public function getTemplateWithWorkflows(JobTemplate $template): array
    {
        $workflows = $template->workflows()
            ->where('is_active', true)
            ->get()
            ->map(function ($workflow) use ($template) {
                $pivot = $template->workflows()
                    ->where('workflows.id', $workflow->id)
                    ->first()
                    ?->pivot;

                return [
                    'id' => $workflow->id,
                    'name' => $workflow->name,
                    'code' => $workflow->code,
                    'description' => $workflow->description,
                    'is_default' => $pivot?->is_default ?? false,
                ];
            })
            ->toArray();

        return [
            'template' => [
                'id' => $template->id,
                'name' => $template->name,
                'code' => $template->code,
                'description' => $template->description,
                'icon' => $template->icon,
                'color' => $template->color,
            ],
            'workflows' => $workflows,
        ];
    }
}
