<?php

namespace Database\Factories\Kps;

use App\Models\Kps\Site;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kps\Site>
 */
class SiteFactory extends Factory
{
    protected $model = Site::class;

    public function definition(): array
    {
        return [
            'name' => 'KPS Site ' . strtoupper(Str::random(4)),
            'code' => 'KPS-' . strtoupper(Str::random(3)),
            'address' => 'Alamat Tapak KPS',
            'phone' => '01' . random_int(10000000, 99999999),
            'email' => 'site-' . Str::lower(Str::random(4)) . '@kps.local',
            'is_active' => true,
        ];
    }
}
