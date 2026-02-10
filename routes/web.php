<?php

use App\Http\Controllers\Admin\RoleManagementController;
use App\Http\Controllers\Admin\UserManagementController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        // Redirect to KPS dashboard if available
        if (Route::has('kps.dashboard')) {
            return redirect()->route('kps.dashboard');
        }
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Admin Routes (Pentadbiran only)
    Route::prefix('admin')->name('admin.')->middleware(['role:pentadbiran'])->group(function () {
        // Role and Permission Management
        Route::resource('roles', RoleManagementController::class);
        Route::get('roles-permissions/view', [RoleManagementController::class, 'permissions'])
            ->name('roles.permissions');
        Route::post('roles-permissions/update', [RoleManagementController::class, 'updatePermissionMatrix'])
            ->name('roles.permissions.update');
        
        // User Management
        Route::resource('users', UserManagementController::class);
        Route::patch('users/{user}/toggle-activation', [UserManagementController::class, 'toggleActivation'])
            ->name('users.toggle-activation');
    });
});

require __DIR__.'/settings.php';
require __DIR__.'/kps.php';
