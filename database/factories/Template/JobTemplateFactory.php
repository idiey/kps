<?php

namespace Database\Factories\Template;

use App\Models\Template\JobTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Template\JobTemplate>
 */
class JobTemplateFactory extends Factory
{
    protected $model = JobTemplate::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'code' => fake()->unique()->slug(2),
            'description' => fake()->sentence(),
            'is_active' => true,
            'is_default' => false,
            'default_workflow_id' => null,
            'created_by' => null,
            'updated_by' => null,
        ];
    }

    /**
     * Indicate that the template is the default.
     */
    public function default(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_default' => true,
        ]);
    }

    /**
     * Indicate that the template is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
