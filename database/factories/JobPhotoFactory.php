<?php

namespace Database\Factories;

use App\Enums\PhotoStage;
use App\Models\InspectionReport;
use App\Models\User;
use App\Models\WorkshopJob;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobPhoto>
 */
class JobPhotoFactory extends Factory
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
            'user_id' => User::factory(),
            'inspection_report_id' => null,
            'photo_stage' => fake()->randomElement(PhotoStage::cases()),
            'file_path' => 'photos/' . fake()->uuid() . '.jpg',
            'file_size' => fake()->numberBetween(100000, 5000000),
            'mime_type' => 'image/jpeg',
            'original_filename' => fake()->word() . '.jpg',
            'description' => fake()->optional()->sentence(),
            'location_context' => fake()->optional()->word(),
            'is_public' => fake()->boolean(70),
            'taken_at' => fake()->dateTimeBetween('-7 days', 'now'),
        ];
    }

    /**
     * Indicate that the photo is public.
     */
    public function public(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_public' => true,
        ]);
    }

    /**
     * Indicate that the photo is private.
     */
    public function private(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_public' => false,
        ]);
    }

    /**
     * Set the photo stage.
     */
    public function stage(PhotoStage $stage): static
    {
        return $this->state(fn (array $attributes) => [
            'photo_stage' => $stage,
        ]);
    }

    /**
     * Attach to an inspection report.
     */
    public function forInspection(InspectionReport $report): static
    {
        return $this->state(fn (array $attributes) => [
            'inspection_report_id' => $report->id,
            'workshop_job_id' => $report->workshop_job_id,
            'photo_stage' => PhotoStage::DIAGNOSTIC,
        ]);
    }
}
