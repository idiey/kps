<?php

use App\Models\Kps\MonthlyDeduction;
use App\Models\Kps\Peneroka;
use App\Models\Kps\Site;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\KpsDemoFlowSeeder;
use Database\Seeders\KpsSiteSeeder;
use Database\Seeders\RolePermissionSeeder;

test('database seeder includes the dedicated demo flow site', function () {
    $this->seed(DatabaseSeeder::class);

    expect(Site::where('code', 'FELDA-DEMO')->exists())->toBeTrue();
});

test('demo flow seeder is idempotent for site, admin, and demo peneroka cases', function () {
    $this->seed([
        RolePermissionSeeder::class,
        KpsSiteSeeder::class,
        KpsDemoFlowSeeder::class,
    ]);

    $this->seed(KpsDemoFlowSeeder::class);

    $demoSite = Site::where('code', 'FELDA-DEMO')->firstOrFail();

    expect(User::where('email', 'demo-site-admin@felda.gov.my')->count())->toBe(1);
    expect(Peneroka::where('site_id', $demoSite->id)->count())->toBe(4);
    expect(MonthlyDeduction::where('site_id', $demoSite->id)->count())->toBe(6);
});

test('demo flow seeder exposes open and closed payment-flow data through site routes', function () {
    $this->seed([
        RolePermissionSeeder::class,
        KpsSiteSeeder::class,
        KpsDemoFlowSeeder::class,
    ]);

    $site = Site::where('code', 'FELDA-DEMO')->firstOrFail();
    $admin = User::where('email', 'demo-site-admin@felda.gov.my')->firstOrFail();

    $this->actingAs($admin)
        ->get("/kps/sites/{$site->id}/potongan?month=2026-04")
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('site.code', 'FELDA-DEMO')
            ->where('selectedMonth', '2026-04')
            ->has('deductions.data', 3)
        );

    $this->actingAs($admin)
        ->get("/kps/sites/{$site->id}/allocations?month=2026-03")
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('site.code', 'FELDA-DEMO')
            ->where('selectedMonth', '2026-03')
            ->has('deductions.data', 2)
        );

    expect(MonthlyDeduction::where('site_id', $site->id)->where('month', '2026-04-01')->where('is_closed', false)->count())->toBe(3);
    expect(MonthlyDeduction::where('site_id', $site->id)->where('month', '2026-03-01')->where('is_closed', true)->count())->toBe(2);
});

test('demo flow seeder exposes statement-ready peneroka data through reports', function () {
    $this->seed([
        RolePermissionSeeder::class,
        KpsSiteSeeder::class,
        KpsDemoFlowSeeder::class,
    ]);

    $site = Site::where('code', 'FELDA-DEMO')->firstOrFail();
    $admin = User::where('email', 'demo-site-admin@felda.gov.my')->firstOrFail();
    $peneroka = Peneroka::where('site_id', $site->id)
        ->where('name', 'Dahlia Demo Penyata')
        ->firstOrFail();

    $this->actingAs($admin)
        ->get("/kps/sites/{$site->id}/reports")
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('site.code', 'FELDA-DEMO')
            ->has('penerokas', 4)
        );

    $this->actingAs($admin)
        ->get("/kps/sites/{$site->id}/reports/peneroka/{$peneroka->id}")
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('peneroka.name', 'Dahlia Demo Penyata')
            ->has('deductions', 3)
            ->has('peneroka.debts', 3)
        );
});
