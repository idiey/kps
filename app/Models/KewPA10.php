<?php

namespace App\Models;

use App\Enums\JobPriority;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class KewPA10 extends Model
{
    use HasFactory;

    protected $table = 'kew_pa_10s';

    protected $fillable = [
        'kew_pa_10_number',
        'government_department_id',
        'asset_id',
        'description',
        'priority',
        'budget_allocation_reference',
        'kew_pa_10_document_path',
        'form_completeness_verified',
        'signatures_verified',
        'verification_notes',
        'received_date',
        'received_by',
    ];

    protected function casts(): array
    {
        return [
            'priority' => JobPriority::class,
            'form_completeness_verified' => 'boolean',
            'signatures_verified' => 'boolean',
            'received_date' => 'date',
        ];
    }

    /**
     * Get the government department that submitted this KEW.PA-10.
     */
    public function governmentDepartment(): BelongsTo
    {
        return $this->belongsTo(GovernmentDepartment::class);
    }

    /**
     * Get the asset related to this KEW.PA-10.
     */
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    /**
     * Get the workshop job created from this KEW.PA-10.
     */
    public function workshopJob(): HasOne
    {
        return $this->hasOne(WorkshopJob::class, 'kew_pa_10_id');
    }

    /**
     * Get the user who received this KEW.PA-10.
     */
    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    /**
     * Check if the KEW.PA-10 form is complete.
     */
    public function isComplete(): bool
    {
        return $this->form_completeness_verified
            && $this->signatures_verified
            && !empty($this->kew_pa_10_number)
            && !empty($this->description);
    }

    /**
     * Check if the KEW.PA-10 has been verified.
     */
    public function isVerified(): bool
    {
        return $this->form_completeness_verified && $this->signatures_verified;
    }
}
