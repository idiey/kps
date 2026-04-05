<?php

use App\Models\User;
use Spatie\Permission\Models\Permission;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('guests are redirected to the login page', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

test('hq users are redirected to the kps dashboard', function () {
    Permission::firstOrCreate(['name' => 'kps.manage_sites']);

    $user = User::factory()->create();
    $user->givePermissionTo('kps.manage_sites');
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('kps.dashboard'));
});
