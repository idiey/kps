<?php

namespace App\Models\Kps;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MonthlyDeduction extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'peneroka_id',
        'site_id',
        'month',
        'amount',
        'unallocated_amount',
        'is_closed',
        'closed_at',
    ];

    protected function casts(): array
    {
        return [
            'month' => 'date',
            'is_closed' => 'boolean',
            'closed_at' => 'datetime',
        ];
    }

    public function peneroka(): BelongsTo
    {
        return $this->belongsTo(Peneroka::class);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function allocations(): HasMany
    {
        return $this->hasMany(DeductionAllocation::class);
    }
}
