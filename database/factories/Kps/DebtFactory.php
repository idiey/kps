<?php

namespace Database\Factories\Kps;

use App\Models\Kps\Debt;
use App\Models\Kps\Peneroka;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kps\Debt>
 */
class DebtFactory extends Factory
{
    protected $model = Debt::class;

    public function definition(): array
    {
        $amount = random_int(100, 1000);

        return [
            'peneroka_id' => Peneroka::factory(),
            'priority' => random_int(1, 3),
            'balance' => $amount,
            'original_amount' => $amount,
            'due_date' => now()->addDays(random_int(1, 30))->toDateString(),
            'description' => 'Hutang peneroka',
        ];
    }
}
