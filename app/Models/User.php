<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
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
        'phone',
        'department',
        'active',
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
            'active' => 'boolean',
        ];
    }

    /**
     * Get KPS sites this user is assigned to.
     */
    public function kpsSites()
    {
        return $this->belongsToMany(\App\Models\Kps\Site::class, 'kps_site_user')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Get KPS sites this user is assigned to.
     */
    public function kpsSites()
    {
        return $this->belongsToMany(\App\Models\Kps\Site::class, 'kps_site_user')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Get the first workshop this user is assigned to (any role).
     */
    public function getFirstKpsSite(): ?\App\Models\Kps\Site
    {
        return $this->kpsSites()->first();
    }

    /**
<<<<<<< HEAD
     * Check if user is only assigned to KPS sites (no HQ permissions).
=======
     * Get the first KPS site this user is assigned to.
     */
    public function getFirstKpsSite(): ?\App\Models\Kps\Site
    {
        return $this->kpsSites()->first();
    }

    /**
     * Check if user is an HQ-level user (has company_id set).
>>>>>>> e6e3153e1e76b0a43ae8b77b9f041c9c43ca0dba
     */
    public function isKpsSiteOnly(): bool
    {
        if ($this->hasRole(['pentadbiran', 'company_admin'])) {
            return false;
        }

        return $this->kpsSites()->exists();
    }

    /**
     * Check if user is only assigned to KPS sites (no HQ permissions).
     */
    public function isKpsSiteOnly(): bool
    {
        if ($this->hasRole(['pentadbiran', 'company_admin'])) {
            return false;
        }

        return $this->kpsSites()->exists();
    }
}
