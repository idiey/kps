<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\WorkshopJob;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RepairCompletionReport>
 */
class RepairCompletionReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $partsUsed = [];
        $partCount = fake()->numberBetween(1, 5);
        
        for ($i = 0; $i < $partCount; $i++) {
            $partsUsed[] = [
                'name' => fake()->word() . ' ' . fake()->word(),
                'quantity' => fake()->numberBetween(1, 10),
                'unit_cost' => fake()->randomFloat(2, 10, 500),
            ];
        }

        return [
            'workshop_job_id' => WorkshopJob::factory(),
            'technician_id' => User::factory(),
            'work_description' => fake()->paragraph(),
            'issues_encountered' => fake()->optional()->paragraph(),
            'parts_used' => $partsUsed,
            'time_spent_hours' => fake()->randomFloat(2, 1, 40),
            'total_cost' => fake()->randomFloat(2, 500, 5000),
            'quality_rating' => fake()->numberBetween(1, 5),
            'recommendations' => fake()->optional()->sentence(),
            'work_completed' => fake()->boolean(80),
            'technician_signature' => null,
            'technician_signed_at' => fake()->dateTimeBetween('-7 days', 'now'),
        ];
    }

    /**
     * Indicate that the report has been signed.
     */
    public function signed(): static
    {
        return $this->state(fn (array $attributes) => [
            'technician_signature' => 'data:image/png;base64,' . base64_encode(fake()->text(100)),
            'technician_signed_at' => now(),
        ]);
    }

    /**
     * Indicate that the report is unsigned.
     */
    public function unsigned(): static
    {
        return $this->state(fn (array $attributes) => [
            'technician_signature' => null,
            'technician_signed_at' => null,
        ]);
    }

    /**
     * Indicate that the work is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'work_completed' => true,
        ]);
    }

    /**
     * Indicate that the work is incomplete.
     */
    public function incomplete(): static
    {
        return $this->state(fn (array $attributes) => [
            'work_completed' => false,
        ]);
    }
}
