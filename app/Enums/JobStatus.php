<?php

namespace App\Enums;

enum JobStatus: string
{
    case NEW = 'new';
    case PENDING_INSPECTION = 'pending_inspection';
    case INSPECTION_IN_PROGRESS = 'inspection_in_progress';
    case INSPECTION_APPROVED = 'inspection_approved';
    case INSPECTION_REJECTED = 'inspection_rejected';
    case AWAITING_PARTS = 'awaiting_parts';
    case REPAIR_IN_PROGRESS = 'repair_in_progress';
    case PENDING_REVIEW = 'pending_review';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case PENDING_KEW_PA_10_RETURN = 'pending_kew_pa_10_return';
    case KEW_PA_10_RETURNED = 'kew_pa_10_returned';
    case INVOICED = 'invoiced';
    case CANCELLED = 'cancelled';

    /**
     * Get the display label for the status.
     */
    public function label(): string
    {
        return match($this) {
            self::NEW => 'New',
            self::PENDING_INSPECTION => 'Pending Inspection',
            self::INSPECTION_IN_PROGRESS => 'Inspection In Progress',
            self::INSPECTION_APPROVED => 'Inspection Approved',
            self::INSPECTION_REJECTED => 'Inspection Rejected',
            self::AWAITING_PARTS => 'Awaiting Parts',
            self::REPAIR_IN_PROGRESS => 'Repair In Progress',
            self::PENDING_REVIEW => 'Pending Review',
            self::IN_PROGRESS => 'In Progress',
            self::COMPLETED => 'Completed',
            self::PENDING_KEW_PA_10_RETURN => 'Pending KEW.PA-10 Return',
            self::KEW_PA_10_RETURNED => 'KEW.PA-10 Returned',
            self::INVOICED => 'Invoiced',
            self::CANCELLED => 'Cancelled',
        };
    }

    /**
     * Get the color/badge class for the status.
     */
    public function color(): string
    {
        return match($this) {
            self::NEW => 'blue',
            self::PENDING_INSPECTION => 'cyan',
            self::INSPECTION_IN_PROGRESS => 'indigo',
            self::INSPECTION_APPROVED => 'teal',
            self::INSPECTION_REJECTED => 'red',
            self::AWAITING_PARTS => 'orange',
            self::REPAIR_IN_PROGRESS => 'amber',
            self::PENDING_REVIEW => 'violet',
            self::IN_PROGRESS => 'yellow',
            self::COMPLETED => 'green',
            self::PENDING_KEW_PA_10_RETURN => 'sky',
            self::KEW_PA_10_RETURNED => 'emerald',
            self::INVOICED => 'purple',
            self::CANCELLED => 'gray',
        };
    }

    /**
     * Get all valid status transitions from current status.
     */
    public function allowedTransitions(): array
    {
        return match($this) {
            self::NEW => [self::PENDING_INSPECTION, self::IN_PROGRESS, self::CANCELLED],
            self::PENDING_INSPECTION => [self::INSPECTION_IN_PROGRESS, self::CANCELLED],
            self::INSPECTION_IN_PROGRESS => [self::INSPECTION_APPROVED, self::INSPECTION_REJECTED],
            self::INSPECTION_APPROVED => [self::REPAIR_IN_PROGRESS, self::AWAITING_PARTS],
            self::INSPECTION_REJECTED => [self::NEW, self::CANCELLED],
            self::AWAITING_PARTS => [self::REPAIR_IN_PROGRESS],
            self::REPAIR_IN_PROGRESS => [self::PENDING_REVIEW, self::AWAITING_PARTS],
            self::PENDING_REVIEW => [self::COMPLETED, self::REPAIR_IN_PROGRESS],
            self::IN_PROGRESS => [self::COMPLETED, self::AWAITING_PARTS],
            self::COMPLETED => [self::PENDING_KEW_PA_10_RETURN, self::INVOICED, self::IN_PROGRESS],
            self::PENDING_KEW_PA_10_RETURN => [self::KEW_PA_10_RETURNED],
            self::KEW_PA_10_RETURNED => [self::INVOICED],
            self::INVOICED => [],
            self::CANCELLED => [],
        };
    }

    /**
     * Check if transition to given status is allowed.
     */
    public function canTransitionTo(JobStatus $targetStatus): bool
    {
        return in_array($targetStatus, $this->allowedTransitions(), true);
    }

    /**
     * Get all statuses as array.
     */
    public static function toArray(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }

    /**
     * Get all statuses with labels.
     */
    public static function options(): array
    {
        return array_map(
            fn($case) => ['value' => $case->value, 'label' => $case->label(), 'color' => $case->color()],
            self::cases()
        );
    }
}
