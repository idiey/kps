<?php

namespace App\Enums;

enum KewPa10Priority: string
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';
    case URGENT = 'urgent';

    /**
     * Get the display label for the priority.
     */
    public function label(): string
    {
        return match ($this) {
            self::LOW => 'Low',
            self::MEDIUM => 'Medium',
            self::HIGH => 'High',
            self::URGENT => 'Urgent',
        };
    }

    /**
     * Get a description for the priority.
     */
    public function description(): string
    {
        return match ($this) {
            self::LOW => 'Non-critical maintenance, can be scheduled',
            self::MEDIUM => 'Standard priority, normal processing',
            self::HIGH => 'Important, requires prompt attention',
            self::URGENT => 'Critical issue, immediate action required',
        };
    }

    /**
     * Get color for the priority.
     */
    public function color(): string
    {
        return match ($this) {
            self::LOW => 'gray',
            self::MEDIUM => 'blue',
            self::HIGH => 'orange',
            self::URGENT => 'red',
        };
    }

    /**
     * Get badge color for the priority.
     */
    public function badgeColor(): string
    {
        return match ($this) {
            self::LOW => 'secondary',
            self::MEDIUM => 'info',
            self::HIGH => 'warning',
            self::URGENT => 'danger',
        };
    }

    /**
     * Get icon for the priority.
     */
    public function icon(): string
    {
        return match ($this) {
            self::LOW => 'arrow-down',
            self::MEDIUM => 'minus',
            self::HIGH => 'arrow-up',
            self::URGENT => 'exclamation-triangle',
        };
    }

    /**
     * Get expected response time in days.
     */
    public function expectedResponseDays(): int
    {
        return match ($this) {
            self::LOW => 30,      // 1 month
            self::MEDIUM => 14,   // 2 weeks
            self::HIGH => 7,      // 1 week
            self::URGENT => 1,    // 1 day
        };
    }

    /**
     * Get SLA hours for this priority.
     */
    public function slaHours(): int
    {
        return match ($this) {
            self::LOW => 720,     // 30 days
            self::MEDIUM => 336,  // 14 days
            self::HIGH => 168,    // 7 days
            self::URGENT => 24,   // 1 day
        };
    }

    /**
     * Get all priorities.
     */
    public static function all(): array
    {
        return [
            self::LOW,
            self::MEDIUM,
            self::HIGH,
            self::URGENT,
        ];
    }

    /**
     * Get all priorities as select options.
     */
    public static function options(): array
    {
        return array_map(
            fn(self $priority) => [
                'value' => $priority->value,
                'label' => $priority->label(),
                'description' => $priority->description(),
                'color' => $priority->color(),
                'badgeColor' => $priority->badgeColor(),
                'icon' => $priority->icon(),
                'expectedDays' => $priority->expectedResponseDays(),
                'slaHours' => $priority->slaHours(),
            ],
            self::all()
        );
    }

    /**
     * Check if priority requires immediate action.
     */
    public function isUrgent(): bool
    {
        return $this === self::URGENT;
    }

    /**
     * Check if priority is high or urgent.
     */
    public function isHighOrAbove(): bool
    {
        return in_array($this, [self::HIGH, self::URGENT]);
    }
}
