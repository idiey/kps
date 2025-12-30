<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GovernmentDepartment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'department_code',
        'ministry',
        'contact_person',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'postcode',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get all assets for this department.
     */
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }

    /**
     * Get all KEW.PA-10 forms received from this department.
     */
    public function kewPA10s(): HasMany
    {
        return $this->hasMany(KewPA10::class);
    }

    /**
     * Scope to filter active departments.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to search departments by name, code, or ministry.
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('department_code', 'like', "%{$search}%")
                ->orWhere('ministry', 'like', "%{$search}%");
        });
    }
}
