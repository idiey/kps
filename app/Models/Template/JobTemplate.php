<?php

namespace App\Models\Template;

use App\Models\User;
use App\Models\Workflow\Workflow;
use App\Models\WorkshopJob;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobTemplate extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'icon',
        'color',
        'is_active',
        'is_default',
        'default_workflow_id',
        'metadata',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_default' => 'boolean',
            'metadata' => 'array',
        ];
    }

    /**
     * Get the user who created this template.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this template.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the default workflow for this template.
     */
    public function defaultWorkflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class, 'default_workflow_id');
    }

    /**
     * Get all fields for this template.
     */
    public function fields(): HasMany
    {
        return $this->hasMany(TemplateField::class, 'template_id')->orderBy('display_order');
    }

    /**
     * Get fields grouped by section.
     */
    public function fieldsBySection()
    {
        return $this->fields()
            ->get()
            ->groupBy('section');
    }

    /**
     * Get all workflows associated with this template.
     */
    public function workflows(): BelongsToMany
    {
        return $this->belongsToMany(Workflow::class, 'template_workflows', 'template_id', 'workflow_id')
            ->withPivot('is_default')
            ->withTimestamps();
    }

    /**
     * Get all jobs using this template.
     */
    public function jobs(): HasMany
    {
        return $this->hasMany(WorkshopJob::class, 'template_id');
    }

    /**
     * Scope to filter active templates.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get default template.
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Get the form schema for dynamic form rendering.
     */
    public function getFormSchema(): array
    {
        $fields = $this->fields()
            ->with('fieldType')
            ->orderBy('section')
            ->orderBy('display_order')
            ->get();

        return $fields->groupBy('section')->map(function ($sectionFields) {
            return $sectionFields->map(function ($field) {
                return [
                    'id' => $field->id,
                    'code' => $field->code,
                    'type' => $field->fieldType->code,
                    'label' => $field->name,
                    'placeholder' => $field->placeholder,
                    'defaultValue' => $field->default_value,
                    'required' => $field->is_required,
                    'validation' => $field->validation_rules,
                    'conditionalRules' => $field->conditional_rules,
                    'options' => $field->options,
                    'formula' => $field->formula,
                    'component' => $field->fieldType->component_name,
                    'gridColumn' => $field->grid_column_span,
                    'helpText' => $field->help_text,
                    'tooltip' => $field->tooltip,
                    'metadata' => $field->metadata,
                ];
            });
        })->toArray();
    }
}
