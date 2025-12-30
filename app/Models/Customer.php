<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'company_name',
        'ic_number',
        'address',
        'city',
        'state',
        'postcode',
        'customer_type',
        'department',
        'notes',
    ];

    /**
     * Get the jobs associated with this customer.
     */
    public function jobs(): HasMany
    {
        return $this->hasMany(WorkshopJob::class);
    }

    /**
     * Get the customer's full address.
     */
    public function getFullAddressAttribute(): ?string
    {
        $parts = array_filter([
            $this->address,
            $this->postcode . ' ' . $this->city,
            $this->state,
        ]);

        return !empty($parts) ? implode(', ', $parts) : null;
    }

    /**
     * Scope to filter by customer type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('customer_type', $type);
    }

    /**
     * Scope to search customers.
     */
    public function scopeSearch($query, ?string $search)
    {
        if (!$search) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhere('company_name', 'like', "%{$search}%")
                ->orWhere('ic_number', 'like', "%{$search}%");
        });
    }
}
