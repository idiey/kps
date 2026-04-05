<?php

namespace App\Models\Kps;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Site extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'address',
        'phone',
        'email',
        'is_active',
        'hutang_weightage_pct',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'hutang_weightage_pct' => 'float',
        ];
    }

    public function penerokas(): HasMany
    {
        return $this->hasMany(Peneroka::class);
    }

    public function monthlyDeductions(): HasMany
    {
        return $this->hasMany(MonthlyDeduction::class);
    }

    public function assignedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'kps_site_user')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function assignUser(int $userId, string $role = 'staff'): void
    {
        $this->assignedUsers()->syncWithoutDetaching([
            $userId => ['role' => $role],
        ]);
    }

    public function removeUser(int $userId): void
    {
        $this->assignedUsers()->detach($userId);
    }

    public function hasUser(int $userId): bool
    {
        return $this->assignedUsers()->where('user_id', $userId)->exists();
    }

    public function isSiteAdmin(int $userId): bool
    {
        return $this->assignedUsers()
            ->where('user_id', $userId)
            ->wherePivot('role', 'site_admin')
            ->exists();
    }

    public function getUserRole(int $userId): ?string
    {
        $user = $this->assignedUsers()->where('user_id', $userId)->first();
        return $user?->pivot?->role;
    }
}
