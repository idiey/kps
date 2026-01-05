<?php

namespace App\Models\Template;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TemplateFieldType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'component_name',
        'validation_schema',
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
            'validation_schema' => 'array',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get all fields of this type.
     */
    public function fields(): HasMany
    {
        return $this->hasMany(TemplateField::class, 'field_type_id');
    }

    /**
     * Scope to filter active field types.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get field types as options for dropdowns.
     */
    public static function getOptions(): array
    {
        return static::active()
            ->orderBy('name')
            ->get()
            ->map(fn ($type) => [
                'value' => $type->id,
                'label' => $type->name,
                'code' => $type->code,
                'component' => $type->component_name,
            ])
            ->toArray();
    }
}
