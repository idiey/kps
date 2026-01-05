<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\WorkshopJob;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobNote>
 */
class JobNoteFactory extends Factory
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
            'content' => fake()->paragraph(),
            'is_public' => fake()->boolean(70),
            'note_type' => fake()->randomElement(['general', 'diagnostic', 'repair', 'parts', 'customer_communication']),
            'attachments' => [],
        ];
    }
}

