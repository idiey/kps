<?php

namespace Database\Seeders;

use App\Models\Kps\Site;
use Illuminate\Database\Seeder;

class KpsSiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Setting up FELDA sites...');

        $sites = [
            [
                'code' => 'FELDA-ST',
                'name' => 'FELDA Sungai Tekam',
                'address' => 'Jalan FELDA Sungai Tekam, 28300 Karak, Pahang',
                'phone' => '09-456 7890',
                'email' => 'st@felda.gov.my',
            ],
            [
                'code' => 'FELDA-JK',
                'name' => 'FELDA Jengka',
                'address' => 'Bandar Pusat Jengka, 26400 Bandar Jengka, Pahang',
                'phone' => '09-466 1234',
                'email' => 'jengka@felda.gov.my',
            ],
            [
                'code' => 'FELDA-TL',
                'name' => 'FELDA Taib Andak',
                'address' => 'FELDA Taib Andak, 86800 Mersing, Johor',
                'phone' => '07-799 5678',
                'email' => 'taibandak@felda.gov.my',
            ],
            [
                'code' => 'FELDA-SB',
                'name' => 'FELDA Serting Baharu',
                'address' => 'FELDA Serting Baharu, 27650 Bandar Bera, Pahang',
                'phone' => '09-277 3344',
                'email' => 'serting@felda.gov.my',
            ],
            [
                'code' => 'FELDA-LP',
                'name' => 'FELDA Lui Panjang',
                'address' => 'FELDA Lui Panjang, 27650 Bandar Bera, Pahang',
                'phone' => '09-277 5566',
                'email' => 'luipanjang@felda.gov.my',
            ],
        ];

        foreach ($sites as $siteData) {
            Site::firstOrCreate(
                ['code' => $siteData['code']],
                array_merge($siteData, [
                    'is_active' => true,
                ])
            );

            $this->command->info("  ✓ Created site: {$siteData['name']}");
        }

        $this->command->info('FELDA sites setup completed!');
    }
}
