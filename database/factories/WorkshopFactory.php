<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Workshop;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Workshop>
 */
class WorkshopFactory extends Factory
{
    protected $model = Workshop::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'name' => $this->faker->company() . ' Workshop',
            'code' => strtoupper($this->faker->unique()->lexify('WS-???')),
            'address' => $this->faker->address(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->companyEmail(),
            'operating_hours' => [
                'mon' => ['open' => '08:00', 'close' => '17:00'],
                'tue' => ['open' => '08:00', 'close' => '17:00'],
                'wed' => ['open' => '08:00', 'close' => '17:00'],
                'thu' => ['open' => '08:00', 'close' => '17:00'],
                'fri' => ['open' => '08:00', 'close' => '17:00'],
            ],
            'is_active' => true,
        ];
    }

    /**
     * Indicate that the workshop is inactive.
     */
    public function inactive(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Create a workshop for a specific company.
     */
    public function forCompany(Company $company): Factory
    {
        return $this->state(fn (array $attributes) => [
            'company_id' => $company->id,
        ]);
    }
}
