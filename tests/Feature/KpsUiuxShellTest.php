<?php

use App\Models\Kps\Site;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Vite;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

beforeAll(function () {
    app()->bind(Vite::class, fn () => new class extends Vite {
        public function __invoke($entrypoints, $buildDirectory = null): \Illuminate\Support\HtmlString {
            return new \Illuminate\Support\HtmlString('');
        }
    });
});

beforeEach(function () {
    foreach (['kps.view', 'kps.manage_sites', 'kps.view_reports'] as $permission) {
        Permission::firstOrCreate(['name' => $permission]);
    }

    $this->site = Site::factory()->create();

    $this->hqUser = User::factory()->create();
    $this->hqUser->givePermissionTo('kps.manage_sites');

    $this->siteViewer = User::factory()->create();
    $this->siteViewer->givePermissionTo('kps.view');
    $this->site->assignUser($this->siteViewer->id, 'staff');

    $this->reportReader = User::factory()->create();
    $this->reportReader->givePermissionTo(['kps.view', 'kps.view_reports']);
    $this->site->assignUser($this->reportReader->id, 'site_admin');
});

test('live shell routes stay accessible', function () {
    $this->actingAs($this->hqUser)
        ->get('/kps/dashboard')
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Kps/Dashboard')
            ->has('stats.sites')
        );

    $this->actingAs($this->siteViewer)
        ->get("/kps/sites/{$this->site->id}")
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Kps/Sites/Show')
            ->where('site.id', $this->site->id)
        );
});

test('site reports still require report permission', function () {
    $this->actingAs($this->siteViewer)
        ->get("/kps/sites/{$this->site->id}/reports")
        ->assertForbidden();

    $this->actingAs($this->reportReader)
        ->get("/kps/sites/{$this->site->id}/reports")
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Kps/Reports/Index')
            ->where('site.id', $this->site->id)
            ->has('penerokas')
        );
});

test('deleted preview routes return 404', function () {
    $this->actingAs($this->hqUser)
        ->get('/kps/preview')
        ->assertNotFound();

    $this->actingAs($this->hqUser)
        ->get('/kps/preview/stitch')
        ->assertNotFound();

    $this->actingAs($this->hqUser)
        ->get('/kps/preview/stitch/dashboard')
        ->assertNotFound();

    $this->actingAs($this->hqUser)
        ->get("/kps/preview/stitch/sites/{$this->site->id}/dashboard")
        ->assertNotFound();

    $this->actingAs($this->hqUser)
        ->get("/kps/preview/stitch/sites/{$this->site->id}/reports")
        ->assertNotFound();
});
