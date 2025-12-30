<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_tag',
        'asset_type',
        'asset_name',
        'description',
        'location',
        'current_condition',
        'last_maintenance_date',
        'government_department_id',
    ];

    protected function casts(): array
    {
        return [
            'last_maintenance_date' => 'date',
        ];
    }

    /**
     * Get the government department that owns this asset.
     */
    public function governmentDepartment(): BelongsTo
    {
        return $this->belongsTo(GovernmentDepartment::class);
    }

    /**
     * Get all workshop jobs for this asset.
     */
    public function workshopJobs(): HasMany
    {
        return $this->hasMany(WorkshopJob::class);
    }

    /**
     * Scope to filter by asset type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('asset_type', $type);
    }

    /**
     * Scope to filter by department.
     */
    public function scopeForDepartment($query, int $departmentId)
    {
        return $query->where('government_department_id', $departmentId);
    }

    /**
     * Scope to search assets.
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('asset_tag', 'like', "%{$search}%")
                ->orWhere('asset_name', 'like', "%{$search}%")
                ->orWhere('asset_type', 'like', "%{$search}%")
                ->orWhere('location', 'like', "%{$search}%");
        });
    }
}
