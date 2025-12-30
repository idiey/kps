<?php

namespace Database\Factories;

use App\Enums\JobPriority;
use App\Models\Asset;
use App\Models\GovernmentDepartment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KewPA10>
 */
class KewPA10Factory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kew_pa_10_number' => 'KEW.PA-10/' . fake()->year() . '/' . fake()->unique()->numerify('####'),
            'government_department_id' => GovernmentDepartment::factory(),
            'asset_id' => Asset::factory(),
            'description' => fake()->paragraph(),
            'priority' => fake()->randomElement([JobPriority::LOW, JobPriority::MEDIUM, JobPriority::HIGH, JobPriority::URGENT]),
            'budget_allocation_reference' => fake()->optional()->numerify('BUD-####-####'),
            'kew_pa_10_document_path' => fake()->optional()->filePath(),
            'form_completeness_verified' => fake()->boolean(70),
            'signatures_verified' => fake()->boolean(70),
            'verification_notes' => fake()->optional()->sentence(),
            'received_date' => fake()->dateTimeBetween('-30 days', 'now'),
            'received_by' => User::factory(),
        ];
    }

    /**
     * Indicate that the KEW.PA-10 is verified.
     */
    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'form_completeness_verified' => true,
            'signatures_verified' => true,
        ]);
    }

    /**
     * Indicate that the KEW.PA-10 is unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'form_completeness_verified' => false,
            'signatures_verified' => false,
        ]);
    }
}
