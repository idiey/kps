<?php

use App\Models\Kps\Site;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

test('role permission seeder normalizes assigned site users onto kps roles', function () {
    $legacyRole = Role::create([
        'name' => 'penyelia',
        'guard_name' => 'web',
    ]);

    $site = Site::factory()->create();
    $siteAdmin = User::factory()->create();
    $siteStaff = User::factory()->create();

    $siteAdmin->assignRole($legacyRole);
    $siteStaff->assignRole($legacyRole);

    $site->assignUser($siteAdmin->id, 'site_admin');
    $site->assignUser($siteStaff->id, 'staff');

    $this->seed(RolePermissionSeeder::class);

    expect($siteAdmin->fresh()->hasRole('site_admin'))->toBeTrue();
    expect($siteStaff->fresh()->hasRole('staff'))->toBeTrue();
    expect($siteAdmin->fresh()->hasRole('penyelia'))->toBeFalse();
    expect($siteStaff->fresh()->hasRole('penyelia'))->toBeFalse();
    expect(Role::findByName('site_admin')->hasPermissionTo('kps.approve_month'))->toBeTrue();
});
