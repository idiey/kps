<?php

namespace Database\Factories\Workflow;

use App\Models\Workflow\WorkflowStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Workflow\WorkflowStatus>
 */
class WorkflowStatusFactory extends Factory
{
    protected $model = WorkflowStatus::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'workflow_id' => null, // Should be set when using the factory
            'name' => fake()->words(2, true),
            'code' => fake()->unique()->slug(2),
            'description' => fake()->optional()->sentence(),
            'color' => fake()->hexColor(),
            'icon' => fake()->optional()->randomElement(['check', 'clock', 'x', 'alert']),
            'is_initial' => false,
            'is_final' => false,
            'display_order' => fake()->numberBetween(1, 100),
            'required_template_id' => null,
        ];
    }

    /**
     * Indicate that the status is an initial status.
     */
    public function initial(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_initial' => true,
        ]);
    }

    /**
     * Indicate that the status is a final status.
     */
    public function final(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_final' => true,
        ]);
    }
}
