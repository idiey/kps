<?php

namespace App\Models;

use App\Models\Kps\Site;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
     * Get the KPS sites this user is assigned to.
     */
    public function kpsSites(): BelongsToMany
    {
        return $this->belongsToMany(Site::class, 'kps_site_user')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Get the first KPS site this user is assigned to.
     */
    public function getFirstKpsSite(): ?Site
    {
        return $this->kpsSites()->first();
    }

    /**
     * Check if the user has HQ-wide access.
     */
    public function isGlobalAdmin(): bool
    {
        return $this->hasRole('pentadbiran');
    }

    /**
     * Check if the user is scoped only to KPS sites.
     */
    public function isKpsSiteOnly(): bool
    {
        return ! $this->hasRole(['pentadbiran', 'company_admin'])
            && $this->kpsSites()->exists();
    }
}
