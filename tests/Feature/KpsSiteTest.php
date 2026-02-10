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
    Permission::firstOrCreate(['name' => 'kps.view']);
    Permission::firstOrCreate(['name' => 'kps.manage_sites']);

    $this->site = Site::factory()->create();

    $this->hqUser = User::factory()->create();
    $this->hqUser->givePermissionTo('kps.manage_sites');

    $this->siteUser = User::factory()->create();
    $this->siteUser->givePermissionTo('kps.view');
    $this->site->assignUser($this->siteUser->id, 'staff');

    $this->otherSite = Site::factory()->create();
});

test('HQ user can view sites list', function () {
    $this->actingAs($this->hqUser)
        ->get('/kps/sites')
        ->assertOk()
        ->assertInertia(fn ($page) => $page->has('sites.data'));
});

test('site user is redirected to their site when accessing kps home', function () {
    $this->actingAs($this->siteUser)
        ->get('/kps')
        ->assertRedirect("/kps/sites/{$this->site->id}");
});

test('site user can view their site dashboard', function () {
    $this->actingAs($this->siteUser)
        ->get("/kps/sites/{$this->site->id}")
        ->assertOk()
        ->assertInertia(fn ($page) => $page->where('site.id', $this->site->id));
});

test('site user cannot view other site dashboard', function () {
    $this->actingAs($this->siteUser)
        ->get("/kps/sites/{$this->otherSite->id}")
        ->assertForbidden();
});

test('HQ dashboard does not include site context', function () {
    $this->actingAs($this->hqUser)
        ->get('/kps/dashboard')
        ->assertOk()
        ->assertInertia(fn ($page) => $page->missing('site'));
});
