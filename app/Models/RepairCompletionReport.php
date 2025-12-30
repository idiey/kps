<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RepairCompletionReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'workshop_job_id',
        'technician_id',
        'work_completed',
        'parts_used',
        'total_cost',
        'time_spent_hours',
        'work_description',
        'issues_encountered',
        'recommendations',
        'quality_rating',
        'technician_signature',
        'technician_signed_at',
    ];

    protected function casts(): array
    {
        return [
            'work_completed' => 'boolean',
            'parts_used' => 'array',
            'total_cost' => 'decimal:2',
            'time_spent_hours' => 'decimal:2',
            'quality_rating' => 'integer',
            'technician_signed_at' => 'datetime',
        ];
    }

    /**
     * Get the workshop job this report is for.
     */
    public function workshopJob(): BelongsTo
    {
        return $this->belongsTo(WorkshopJob::class);
    }

    /**
     * Get the technician who completed the work.
     */
    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    /**
     * Check if the report is signed.
     */
    public function isSigned(): bool
    {
        return !empty($this->technician_signature) && !is_null($this->technician_signed_at);
    }

    /**
     * Add a part to the parts used list.
     */
    public function addPart(string $name, int $quantity, float $cost): void
    {
        $parts = $this->parts_used ?? [];
        $parts[] = [
            'name' => $name,
            'quantity' => $quantity,
            'cost' => $cost,
        ];
        $this->parts_used = $parts;
        $this->total_cost = collect($parts)->sum(fn($part) => $part['cost'] * $part['quantity']);
        $this->save();
    }
}
