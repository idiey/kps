<?php

namespace Database\Factories;

use App\Models\GovernmentDepartment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Asset>
 */
class AssetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'asset_tag' => strtoupper(fake()->unique()->bothify('ASSET-??-####')),
            'government_department_id' => GovernmentDepartment::factory(),
            'asset_type' => fake()->randomElement(['vehicle', 'equipment', 'machinery', 'electronics']),
            'asset_name' => fake()->words(3, true),
            'description' => fake()->optional()->sentence(),
            'current_condition' => fake()->randomElement(['excellent', 'good', 'fair', 'poor']),
        ];
    }

    /**
     * Indicate that the asset is a vehicle.
     */
    public function vehicle(): static
    {
        return $this->state(fn (array $attributes) => [
            'asset_type' => 'vehicle',
            'asset_name' => fake()->randomElement(['Toyota Hilux', 'Proton Saga', 'Honda Civic', 'Mitsubishi Triton']),
        ]);
    }

    /**
     * Indicate that the asset is equipment.
     */
    public function equipment(): static
    {
        return $this->state(fn (array $attributes) => [
            'asset_type' => 'equipment',
            'asset_name' => fake()->randomElement(['Power Generator', 'Air Compressor', 'Welding Machine', 'Drilling Equipment']),
        ]);
    }
}
