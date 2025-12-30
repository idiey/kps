<?php

namespace App\Enums;

enum PhotoStage: string
{
    case INITIAL = 'initial';
    case DIAGNOSTIC = 'diagnostic';
    case DURING_REPAIR = 'during_repair';
    case AFTER_REPAIR = 'after_repair';
    case DOCUMENTATION = 'documentation';

    /**
     * Get the display label for the photo stage.
     */
    public function label(): string
    {
        return match($this) {
            self::INITIAL => 'Initial Condition',
            self::DIAGNOSTIC => 'Diagnostic Phase',
            self::DURING_REPAIR => 'During Repair',
            self::AFTER_REPAIR => 'After Repair',
            self::DOCUMENTATION => 'Documentation',
        };
    }

    /**
     * Get the description for the photo stage.
     */
    public function description(): string
    {
        return match($this) {
            self::INITIAL => 'Photos of the asset\'s condition upon receipt',
            self::DIAGNOSTIC => 'Photos documenting the inspection and problem assessment',
            self::DURING_REPAIR => 'Photos taken during the repair process',
            self::AFTER_REPAIR => 'Photos of the completed repair work',
            self::DOCUMENTATION => 'Additional documentation, receipts, and related materials',
        };
    }

    /**
     * Get all photo stages as options array.
     */
    public static function options(): array
    {
        return array_map(
            fn($case) => [
                'value' => $case->value,
                'label' => $case->label(),
                'description' => $case->description()
            ],
            self::cases()
        );
    }
}
