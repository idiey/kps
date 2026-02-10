<?php

namespace Database\Factories\Kps;

use App\Models\Kps\Peneroka;
use App\Models\Kps\Site;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kps\Peneroka>
 */
class PenerokaFactory extends Factory
{
    protected $model = Peneroka::class;

    public function definition(): array
    {
        return [
            'site_id' => Site::factory(),
            'name' => 'Peneroka ' . strtoupper(Str::random(5)),
            'ic_number' => str_pad((string) random_int(0, 999999999999), 12, '0', STR_PAD_LEFT),
            'phone' => '01' . random_int(10000000, 99999999),
            'address' => 'Alamat Peneroka',
        ];
    }
}
