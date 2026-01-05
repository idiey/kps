<?php

namespace App\Models\Job;

use App\Models\Template\TemplateField;
use App\Models\WorkshopJob;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobFieldValue extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'job_id',
        'field_id',
        'value_text',
        'value_number',
        'value_date',
        'value_datetime',
        'value_boolean',
        'value_json',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'value_number' => 'decimal:4',
            'value_date' => 'date',
            'value_datetime' => 'datetime',
            'value_boolean' => 'boolean',
            'value_json' => 'array',
        ];
    }

    /**
     * Get the job this value belongs to.
     */
    public function job(): BelongsTo
    {
        return $this->belongsTo(WorkshopJob::class);
    }

    /**
     * Get the field definition.
     */
    public function field(): BelongsTo
    {
        return $this->belongsTo(TemplateField::class);
    }

    /**
     * Get the actual value based on field type.
     */
    public function getValue()
    {
        $this->loadMissing('field.fieldType');

        $fieldType = $this->field->fieldType->code;

        return match ($fieldType) {
            'text', 'textarea', 'dropdown', 'radio' => $this->value_text,
            'number', 'calculated' => $this->value_number,
            'date' => $this->value_date,
            'datetime' => $this->value_datetime,
            'checkbox' => $this->value_boolean,
            'multiselect', 'file', 'image' => $this->value_json,
            default => $this->value_text,
        };
    }

    /**
     * Set the value based on field type.
     */
    public function setValue($value): void
    {
        $this->loadMissing('field.fieldType');

        $fieldType = $this->field->fieldType->code;

        // Clear all value columns first
        $this->value_text = null;
        $this->value_number = null;
        $this->value_date = null;
        $this->value_datetime = null;
        $this->value_boolean = null;
        $this->value_json = null;

        // Set the appropriate column
        match ($fieldType) {
            'text', 'textarea', 'dropdown', 'radio' => $this->value_text = $value,
            'number', 'calculated' => $this->value_number = $value,
            'date' => $this->value_date = $value,
            'datetime' => $this->value_datetime = $value,
            'checkbox' => $this->value_boolean = $value,
            'multiselect', 'file', 'image' => $this->value_json = $value,
            default => $this->value_text = $value,
        };
    }

    /**
     * Scope to filter by job.
     */
    public function scopeForJob($query, $jobId)
    {
        return $query->where('job_id', $jobId);
    }

    /**
     * Scope to filter by field.
     */
    public function scopeForField($query, $fieldId)
    {
        return $query->where('field_id', $fieldId);
    }

    /**
     * Get field values as associative array (code => value).
     */
    public static function getJobFieldValues(WorkshopJob $job): array
    {
        return static::forJob($job->id)
            ->with('field')
            ->get()
            ->mapWithKeys(function ($fieldValue) {
                return [$fieldValue->field->code => $fieldValue->getValue()];
            })
            ->toArray();
    }
}
