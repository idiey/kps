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
            'job_mode' => \App\Enums\JobMode::NORMAL,
        ];
    }

    /**
     * Indicate that the job is a KEW.PA-10 job.
     */
    public function kew(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'job_mode' => \App\Enums\JobMode::KEW_PA_10,
                'vehicle_registration' => null, // KEW uses its own columns or synchronizes
                'asset_tag' => null,
                'kew_vehicle_registration' => fake()->bothify('W?####?'),
                'kew_asset_tag' => fake()->numerify('KEW-ASSET-####'),
                'kew_pa_10_number' => fake()->numerify('KEW/2025/####'),
                'kew_pa_10_received_date' => fake()->date(),
                'kew_pa_10_description' => fake()->paragraph(),
                'kew_pa_10_priority' => fake()->randomElement(\App\Enums\KewPa10Priority::cases()),
                'kew_findings' => fake()->paragraph(),
                'kew_recommendations' => fake()->paragraph(),
                'kew_inspector_name' => fake()->name(),
                'kew_inspection_date' => fake()->date(),
            ];
        });
    }

    /**
     * Indicate that the job is a Normal job.
     */
    public function normal(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'job_mode' => \App\Enums\JobMode::NORMAL,
            ];
        });
    }

    /**
     * Indicate that the job is pending approval.
     */
    public function pendingApproval(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => \App\Enums\JobStatus::INSPECTION_IN_PROGRESS,
            ];
        });
    }

    /**
     * Indicate that the job is approved.
     */
    public function approved(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => \App\Enums\JobStatus::INSPECTION_APPROVED,
                'kew_approval_status' => 'approved',
                'kew_approved_at' => now(),

            ];
        });
    }

    /**
     * Indicate that the job is rejected.
     */
    public function rejected(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => \App\Enums\JobStatus::INSPECTION_REJECTED,
                'kew_approval_status' => 'rejected',
                'kew_approved_at' => now(),
                'kew_rejection_reason' => fake()->paragraph(),
            ];
        });
    }
}
