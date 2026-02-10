<?php

namespace Database\Factories\Kps;

use App\Models\Kps\MonthlyDeduction;
use App\Models\Kps\Peneroka;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kps\MonthlyDeduction>
 */
class MonthlyDeductionFactory extends Factory
{
    protected $model = MonthlyDeduction::class;

    public function definition(): array
    {
        return [
            'peneroka_id' => Peneroka::factory(),
            'site_id' => function (array $attributes) {
                $peneroka = Peneroka::find($attributes['peneroka_id']);
                return $peneroka?->site_id;
            },
            'month' => now()->startOfMonth()->toDateString(),
            'amount' => random_int(50, 500),
            'unallocated_amount' => 0,
            'is_closed' => false,
        ];
    }
}
