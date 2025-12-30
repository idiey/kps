<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\WorkshopJob;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InspectionReport>
 */
class InspectionReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'workshop_job_id' => WorkshopJob::factory(),
            'inspector_id' => User::factory(),
            'asset_condition_current' => fake()->randomElement(['excellent', 'good', 'fair', 'poor']),
            'visual_damage_assessment' => fake()->paragraph(),
            'functional_testing_results' => fake()->optional()->paragraph(),
            'safety_hazards_identified' => fake()->optional()->sentence(),
            'additional_issues_discovered' => fake()->optional()->paragraph(),
            'recommended_repairs' => fake()->sentence(),
            'approval_status' => 'pending',
            'approval_notes' => null,
            'digital_signature' => null,
            'signed_at' => null,
            'inspection_completed_at' => fake()->dateTimeBetween('-7 days', 'now'),
        ];
    }

    /**
     * Indicate that the inspection is pending approval.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'approval_status' => 'pending',
            'approval_notes' => null,
            'approved_by' => null,
            'approved_at' => null,
        ]);
    }

    /**
     * Indicate that the inspection is approved.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'approval_status' => 'approved',
            'approval_notes' => fake()->optional()->sentence(),
            'signed_at' => now(),
        ]);
    }

    /**
     * Indicate that the inspection is rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'approval_status' => 'rejected',
            'approval_notes' => fake()->sentence(),
            'signed_at' => now(),
        ]);
    }

    /**
     * Indicate that the inspection has been signed.
     */
    public function signed(): static
    {
        return $this->state(fn (array $attributes) => [
            'digital_signature' => 'data:image/png;base64,' . base64_encode(fake()->text(100)),
            'signed_at' => now(),
        ]);
    }
}
