<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GovernmentDepartment>
 */
class GovernmentDepartmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company() . ' Department',
            'department_code' => strtoupper(fake()->unique()->lexify('DEPT-???')),
            'ministry' => 'Ministry of ' . fake()->randomElement(['Education', 'Health', 'Transport', 'Agriculture', 'Defence']),
            'contact_person' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->companyEmail(),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'postcode' => fake()->postcode(),
            'is_active' => true,
        ];
    }

    /**
     * Indicate that the department is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
