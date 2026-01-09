<?php

namespace Database\Factories\Template;

use App\Models\Template\TemplateField;
use App\Models\Template\TemplateFieldType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Template\TemplateField>
 */
class TemplateFieldFactory extends Factory
{
    protected $model = TemplateField::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Get or create a default field type
        $fieldType = TemplateFieldType::firstOrCreate(
            ['code' => 'text'],
            [
                'name' => 'Text',
                'component_name' => 'TextInput',
                'has_options' => false,
            ]
        );

        return [
            'template_id' => null, // Should be set when using the factory
            'field_type_id' => $fieldType->id,
            'name' => fake()->words(2, true),
            'code' => fake()->unique()->slug(2),
            'placeholder' => fake()->optional()->sentence(3),
            'default_value' => null,
            'is_required' => fake()->boolean(30),
            'validation_rules' => null,
            'conditional_rules' => null,
            'options' => null,
            'formula' => null,
            'section' => 'default',
            'display_order' => fake()->numberBetween(1, 100),
            'grid_column_span' => 12,
            'help_text' => fake()->optional()->sentence(),
            'tooltip' => null,
            'metadata' => null,
        ];
    }

    /**
     * Indicate that the field is required.
     */
    public function required(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_required' => true,
        ]);
    }

    /**
     * Set the field as a select type with options.
     */
    public function select(array $options = null): static
    {
        $fieldType = TemplateFieldType::firstOrCreate(
            ['code' => 'select'],
            [
                'name' => 'Select',
                'component_name' => 'Select',
                'has_options' => true,
            ]
        );

        return $this->state(fn (array $attributes) => [
            'field_type_id' => $fieldType->id,
            'options' => $options ?? ['Option 1', 'Option 2', 'Option 3'],
        ]);
    }
}
