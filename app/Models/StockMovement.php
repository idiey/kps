<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'part_id',
        'type',
        'quantity',
        'balance_after',
        'reason',
        'reference',
        'user_id',
    ];

   protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'balance_after' => 'integer',
        ];
    }

    /**
     * Get the part for this movement
     */
    public function part(): BelongsTo
    {
        return $this->belongsTo(Part::class);
    }

    /**
     * Get the user who performed this movement
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
