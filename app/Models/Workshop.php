<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Workshop extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'company_id',
        'name',
        'code',
        'address',
        'phone',
        'email',
        'operating_hours',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'operating_hours' => 'array',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the company (HQ) that owns this workshop (site).
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get all jobs for this workshop.
     */
    public function jobs(): HasMany
    {
        return $this->hasMany(WorkshopJob::class);
    }

    /**
     * Get all customers for this workshop.
     */
    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    /**
     * Get all users assigned to this workshop/site with their roles.
     */
    public function assignedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'workshop_user')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Scope to only active workshops.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter workshops by company (HQ).
     */
    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    /**
     * Assign a user to this workshop/site with a specific role.
     */
    public function assignUser(int $userId, string $role = 'staff'): void
    {
        $this->assignedUsers()->syncWithoutDetaching([
            $userId => ['role' => $role]
        ]);
    }

    /**
     * Remove a user from this workshop/site.
     */
    public function removeUser(int $userId): void
    {
        $this->assignedUsers()->detach($userId);
    }

    /**
     * Get users assigned to this workshop by their role.
     */
    public function usersByRole(string $role)
    {
        return $this->assignedUsers()->wherePivot('role', $role)->get();
    }

    /**
     * Check if a user is assigned to this workshop.
     */
    public function hasUser(int $userId): bool
    {
        return $this->assignedUsers()->where('user_id', $userId)->exists();
    }

    /**
     * Check if a user is a site admin for this workshop.
     */
    public function isSiteAdmin(int $userId): bool
    {
        return $this->assignedUsers()
            ->where('user_id', $userId)
            ->wherePivot('role', 'site_admin')
            ->exists();
    }

    /**
     * Get the user's role at this workshop.
     * Returns null if user is not assigned.
     */
    public function getUserRole(int $userId): ?string
    {
        $user = $this->assignedUsers()->where('user_id', $userId)->first();
        return $user?->pivot?->role;
    }
}

