<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\User;
use App\Models\WorkshopJob;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class VerifySeededData extends Command
{
    protected $signature = 'verify:seeded-data';
    protected $description = 'Verify that database has been seeded correctly';

    public function handle()
    {
        $this->info('Database Seeding Verification');
        $this->info('============================');
        $this->newLine();

        // Count records
        $this->info('Record Counts:');
        $this->table(
            ['Table', 'Count'],
            [
                ['Users', User::count()],
                ['Customers', Customer::count()],
                ['Workshop Jobs', WorkshopJob::count()],
                ['Roles', Role::count()],
                ['Permissions', Permission::count()],
            ]
        );

        $this->newLine();
        $this->info('Users and Their Roles:');
        $users = User::with('roles')->get();
        $userData = [];
        foreach ($users as $user) {
            $userData[] = [
                $user->name,
                $user->email,
                $user->getRoleNames()->implode(', '),
            ];
        }
        $this->table(
            ['Name', 'Email', 'Spatie Roles'],
            $userData
        );

        $this->newLine();
        $this->info('Roles and Permissions:');
        $roles = Role::with('permissions')->get();
        foreach ($roles as $role) {
            $this->info("Role: {$role->name}");
            $this->line('  Permissions: ' . $role->permissions->pluck('name')->implode(', '));
        }

        $this->newLine();
        $this->info('Verification complete!');
    }
}
