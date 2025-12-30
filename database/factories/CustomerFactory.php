<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'company_name' => fake()->company(),
            'ic_number' => fake()->numerify('######-##-####'),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'state' => fake()->randomElement(['Selangor', 'Kuala Lumpur', 'Johor', 'Penang']),
            'postcode' => fake()->postcode(),
            'customer_type' => fake()->randomElement(['individual', 'government', 'corporate']),
            'department' => fake()->randomElement(['IT', 'Finance', 'Operations', null]),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
