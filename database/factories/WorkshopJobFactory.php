<?php

namespace Database\Factories;

use App\Enums\JobPriority;
use App\Enums\JobStatus;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkshopJob>
 */
class WorkshopJobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'title' => fake()->sentence(6),
            'description' => fake()->paragraph(),
            'status' => fake()->randomElement([JobStatus::NEW, JobStatus::IN_PROGRESS, JobStatus::COMPLETED]),
            'priority' => fake()->randomElement([JobPriority::LOW, JobPriority::MEDIUM, JobPriority::HIGH, JobPriority::URGENT]),
            'vehicle_registration' => fake()->optional()->bothify('W?####?'),
            'asset_tag' => fake()->optional()->numerify('ASSET-####'),
            'estimated_cost' => fake()->randomFloat(2, 100, 5000),
            'due_date' => fake()->dateTimeBetween('now', '+30 days'),
        ];
    }
}
