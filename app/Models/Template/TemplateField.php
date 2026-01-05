<?php

namespace App\Models\Template;

use App\Models\Job\JobFieldValue;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TemplateField extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'template_id',
        'field_type_id',
        'name',
        'code',
        'description',
        'placeholder',
        'default_value',
        'section',
        'display_order',
        'grid_column_span',
        'is_required',
        'validation_rules',
        'conditional_rules',
        'options',
        'options_source',
        'options_query',
        'formula',
        'calculation_trigger',
        'help_text',
        'tooltip',
        'metadata',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'validation_rules' => 'array',
            'conditional_rules' => 'array',
            'options' => 'array',
            'metadata' => 'array',
            'is_required' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the template this field belongs to.
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(JobTemplate::class, 'template_id');
    }

    /**
     * Get the field type.
     */
    public function fieldType(): BelongsTo
    {
        return $this->belongsTo(TemplateFieldType::class);
    }

    /**
     * Get all values for this field across jobs.
     */
    public function values(): HasMany
    {
        return $this->hasMany(JobFieldValue::class, 'field_id');
    }

    /**
     * Scope to filter active fields.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by section.
     */
    public function scopeInSection($query, ?string $section)
    {
        if ($section === null) {
            return $query->whereNull('section');
        }

        return $query->where('section', $section);
    }

    /**
     * Get resolved options (handle dynamic sources).
     */
    public function getResolvedOptions(): array
    {
        if ($this->options_source === 'static' || !$this->options_source) {
            return $this->options ?? [];
        }

        if ($this->options_source === 'database' && $this->options_query) {
            // TODO: Execute database query to get options
            // For now, return empty array
            return [];
        }

        if ($this->options_source === 'api' && $this->options_query) {
            // TODO: Call API to get options
            // For now, return empty array
            return [];
        }

        return [];
    }

    /**
     * Evaluate formula for calculated fields.
     */
    public function evaluateFormula(array $fieldValues)
    {
        if (!$this->formula) {
            return null;
        }

        // TODO: Implement formula evaluation
        // Parse formula, replace ${field_code} with actual values
        // Evaluate expression and return result

        return null;
    }

    /**
     * Validate a value against this field's rules.
     */
    public function validateValue($value): array
    {
        $errors = [];

        // Required check
        if ($this->is_required && ($value === null || $value === '')) {
            $errors[] = "{$this->name} is required";
        }

        // Type-specific validation
        // TODO: Implement based on field type and validation_rules

        return $errors;
    }

    /**
     * Check if field should be visible based on conditional rules.
     */
    public function isVisible(array $formData): bool
    {
        if (!$this->conditional_rules || count($this->conditional_rules) === 0) {
            return true;
        }

        // TODO: Evaluate conditional rules
        // Check if dependencies are met
        return true;
    }

    /**
     * Get field configuration for frontend.
     */
    public function toFormConfig(): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'type' => $this->fieldType->code,
            'label' => $this->name,
            'description' => $this->description,
            'placeholder' => $this->placeholder,
            'defaultValue' => $this->default_value,
            'required' => $this->is_required,
            'validation' => $this->validation_rules,
            'conditionalRules' => $this->conditional_rules,
            'options' => $this->getResolvedOptions(),
            'formula' => $this->formula,
            'calculationTrigger' => $this->calculation_trigger,
            'component' => $this->fieldType->component_name,
            'gridColumn' => $this->grid_column_span,
            'helpText' => $this->help_text,
            'tooltip' => $this->tooltip,
            'metadata' => $this->metadata,
        ];
    }
}
