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
        ->assertInertia(fn ($page) => $page
            ->where('site.id', $this->site->id)
            ->has('monthLabel')
            ->has('stats')
            ->has('monthlyTrend')
            ->has('topPeneroka')
            ->has('recentActivity')
        );
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

test('HQ user can update hutang weightage percentage for a site', function () {
    $this->actingAs($this->hqUser)
        ->put("/kps/sites/{$this->site->id}", [
            'name' => $this->site->name,
            'code' => $this->site->code,
            'address' => $this->site->address,
            'phone' => $this->site->phone,
            'email' => $this->site->email,
            'is_active' => $this->site->is_active,
            'hutang_weightage_pct' => 35,
        ])
        ->assertRedirect('/kps/sites');

    expect((float) $this->site->fresh()->hutang_weightage_pct)->toBe(35.0);
});

test('site user can access KPS profile settings page', function () {
    $this->actingAs($this->siteUser)
        ->get('/kps/profile')
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('Kps/Profile'));
});

test('KPS profile update stays in KPS shell', function () {
    $this->actingAs($this->siteUser)
        ->patch('/kps/profile', [
            'name' => 'Updated Site User',
            'email' => 'updated-site-user@example.com',
        ])
        ->assertRedirect('/kps/profile');

    expect($this->siteUser->fresh()->name)->toBe('Updated Site User');
    expect($this->siteUser->fresh()->email)->toBe('updated-site-user@example.com');
});
