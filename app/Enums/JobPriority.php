<?php

namespace App\Enums;

enum JobPriority: string
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
        return match($this) {
            self::LOW => 'Low',
            self::MEDIUM => 'Medium',
            self::HIGH => 'High',
            self::URGENT => 'Urgent',
        };
    }

    /**
     * Get the color/badge class for the priority.
     */
    public function color(): string
    {
        return match($this) {
            self::LOW => 'gray',
            self::MEDIUM => 'blue',
            self::HIGH => 'orange',
            self::URGENT => 'red',
        };
    }

    /**
     * Get all priorities as array.
     */
    public static function toArray(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }

    /**
     * Get all priorities with labels.
     */
    public static function options(): array
    {
        return array_map(
            fn($case) => ['value' => $case->value, 'label' => $case->label(), 'color' => $case->color()],
            self::cases()
        );
    }
}
