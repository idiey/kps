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
     * Check if user has a specific role.
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user can assign jobs.
     */
    public function canAssignJobs(): bool
    {
        return in_array($this->role, ['pentadbiran', 'penyelia']);
    }

    /**
     * Check if user is a technician.
     */
    public function isTechnician(): bool
    {
        return $this->role === 'juruteknik';
    }
}
