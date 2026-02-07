<?php

namespace App\Enums;

enum JobMode: string
{
    case KEW_PA_10 = 'KEW_PA_10';
    case NORMAL = 'NORMAL';

    /**
     * Get the display label for the job mode.
     */
    public function label(): string
    {
        return match ($this) {
            self::KEW_PA_10 => 'KEW.PA-10 (Government)',
            self::NORMAL => 'Normal Workshop Job',
        };
    }

    /**
     * Get a description for the job mode.
     */
    public function description(): string
    {
        return match ($this) {
            self::KEW_PA_10 => 'Government asset maintenance requests processed via KEW.PA-10 form',
            self::NORMAL => 'Standard workshop maintenance and repair jobs',
        };
    }

    /**
     * Get icon for the job mode.
     */
    public function icon(): string
    {
        return match ($this) {
            self::KEW_PA_10 => 'building-columns',  // Government icon
            self::NORMAL => 'wrench',               // Workshop icon
        };
    }

    /**
     * Get color for the job mode.
     */
    public function color(): string
    {
        return match ($this) {
            self::KEW_PA_10 => 'blue',
            self::NORMAL => 'gray',
        };
    }

    /**
     * Check if this mode requires KEW.PA-10 fields.
     */
    public function requiresKewPa10Fields(): bool
    {
        return $this === self::KEW_PA_10;
    }

    /**
     * Get all job modes.
     */
    public static function all(): array
    {
        return [
            self::KEW_PA_10,
            self::NORMAL,
        ];
    }

    /**
     * Get all job modes as select options.
     */
    public static function options(): array
    {
        return array_map(
            fn(self $mode) => [
                'value' => $mode->value,
                'label' => $mode->label(),
                'description' => $mode->description(),
                'icon' => $mode->icon(),
                'color' => $mode->color(),
            ],
            self::all()
        );
    }
}
