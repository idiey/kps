<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Workshop;
use Illuminate\Database\Seeder;

class SiteSeeder extends Seeder
{
    /**
     * The default company name used for all sites.
     */
    public const DEFAULT_COMPANY_NAME = 'VOCM';
    public const DEFAULT_COMPANY_CODE = 'VOCM';

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Setting up default company and sites...');

        // Create or get the default VOCM company
        $vocm = $this->getDefaultCompany();

        // Create sample sites for the default company
        $this->createSites($vocm);

        $this->command->info('Default company and sites setup completed!');
    }

    /**
     * Get or create the default VOCM company.
     */
    public static function getDefaultCompany(): Company
    {
        return Company::firstOrCreate(
            ['name' => self::DEFAULT_COMPANY_NAME],
            [
                'subdomain' => strtolower(self::DEFAULT_COMPANY_CODE),
                'tier' => 'enterprise',
                'settings' => [],
                'is_active' => true,
            ]
        );
    }

    /**
     * Create sample sites for the given company.
     */
    protected function createSites(Company $company): void
    {
        $sites = [
            [
                'code' => 'VOCM-PJ',
                'name' => 'Bengkel VOCM Petaling Jaya',
                'address' => 'Jalan Lapangan Terbang, Subang, Selangor',
                'phone' => '03-7846 1234',
                'email' => 'pj@vocm.gov.my',
            ],
            [
                'code' => 'VOCM-KL',
                'name' => 'Bengkel VOCM Kuala Lumpur',
                'address' => 'Jalan Duta, Kuala Lumpur',
                'phone' => '03-6201 5678',
                'email' => 'kl@vocm.gov.my',
            ],
            [
                'code' => 'VOCM-SHAH',
                'name' => 'Bengkel VOCM Shah Alam',
                'address' => 'Persiaran Kayangan, Shah Alam, Selangor',
                'phone' => '03-5544 3322',
                'email' => 'shahalam@vocm.gov.my',
            ],
            [
                'code' => 'VOCM-JB',
                'name' => 'Bengkel VOCM Johor Bahru',
                'address' => 'Jalan Skudai, Johor Bahru, Johor',
                'phone' => '07-2233 4455',
                'email' => 'jb@vocm.gov.my',
            ],
            [
                'code' => 'VOCM-PNG',
                'name' => 'Bengkel VOCM Pulau Pinang',
                'address' => 'Jalan Bayan Lepas, Pulau Pinang',
                'phone' => '04-6677 8899',
                'email' => 'penang@vocm.gov.my',
            ],
        ];

        foreach ($sites as $siteData) {
            Workshop::firstOrCreate(
                ['code' => $siteData['code']],
                array_merge($siteData, [
                    'company_id' => $company->id,
                    'is_active' => true,
                    'operating_hours' => [
                        'mon' => ['open' => '08:00', 'close' => '17:00'],
                        'tue' => ['open' => '08:00', 'close' => '17:00'],
                        'wed' => ['open' => '08:00', 'close' => '17:00'],
                        'thu' => ['open' => '08:00', 'close' => '17:00'],
                        'fri' => ['open' => '08:00', 'close' => '17:00'],
                    ],
                ])
            );

            $this->command->info("  ✓ Created site: {$siteData['name']}");
        }
    }
}
