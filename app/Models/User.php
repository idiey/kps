<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'department',
        'active',
        'company_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
            'role' => UserRole::class,
        ];
    }

    /**
     * Get the jobs assigned to this user.
     */
    public function assignedJobs()
    {
        return $this->hasMany(WorkshopJob::class, 'assigned_to');
    }

    /**
     * Get jobs created/assigned by this user.
     */
    public function createdAssignments()
    {
        return $this->hasMany(JobAssignment::class, 'assigned_by');
    }

    /**
     * Get assignments for this user.
     */
    public function receivedAssignments()
    {
        return $this->hasMany(JobAssignment::class, 'assigned_to');
    }

    /**
     * Get notes created by this user.
     */
    public function jobNotes()
    {
        return $this->hasMany(JobNote::class);
    }

    /**
     * Get status changes made by this user.
     */
    public function statusChanges()
    {
        return $this->hasMany(JobStatusHistory::class);
    }

    /**
     * Get the company (HQ) this user belongs to.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get workshops/sites this user is assigned to.
     */
    public function assignedWorkshops()
    {
        return $this->belongsToMany(Workshop::class, 'workshop_user')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Check if user is an HQ-level user (has company_id set).
     */
    public function isHqUser(): bool
    {
        return $this->company_id !== null;
    }

    /**
     * Check if user is a global admin (no company restriction).
     */
    public function isGlobalAdmin(): bool
    {
        return $this->company_id === null && $this->hasRole('pentadbiran');
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string|array|\App\Enums\UserRole $role): bool
    {
        if (is_array($role)) {
            foreach ($role as $r) {
                if ($this->hasRole($r)) {
                    return true;
                }
            }
            return false;
        }

        $current = $this->role instanceof \App\Enums\UserRole ? $this->role->value : $this->role;
        $target = $role instanceof \App\Enums\UserRole ? $role->value : $role;
        return $current === $target;
    }

    /**
     * Check if user can assign jobs.
     */
    public function canAssignJobs(): bool
    {
        $value = $this->role instanceof \App\Enums\UserRole ? $this->role->value : $this->role;
        return in_array($value, ['pentadbiran', 'penyelia'], true);
    }

    /**
     * Check if user is a technician.
     */
    public function isTechnician(): bool
    {
        $value = $this->role instanceof \App\Enums\UserRole ? $this->role->value : $this->role;
        return $value === 'juruteknik';
    }

    /**
     * Check if user is a site admin only (has site_admin pivot role but not a global admin).
     * These users should be redirected to their assigned site.
     */
    public function isSiteAdminOnly(): bool
    {
        // If user is a global admin, they're not site-admin-only
        if ($this->isGlobalAdmin()) {
            return false;
        }
        
        // If user has company admin role (pentadbiran), they're not site-admin-only
        if ($this->hasRole('pentadbiran')) {
            return false;
        }

        // Check if they have at least one workshop with site_admin pivot role
        return $this->assignedWorkshops()
            ->wherePivot('role', 'site_admin')
            ->exists();
    }

    /**
     * Get the first workshop where user is a site admin.
     */
    public function getFirstSiteAdminWorkshop(): ?Workshop
    {
        return $this->assignedWorkshops()
            ->wherePivot('role', 'site_admin')
            ->first();
    }
}
