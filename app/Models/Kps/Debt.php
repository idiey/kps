<?php

namespace App\Models\Kps;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Debt extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'peneroka_id',
        'priority',
        'balance',
        'original_amount',
        'due_date',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
        ];
    }

    public function peneroka(): BelongsTo
    {
        return $this->belongsTo(Peneroka::class);
    }
}
