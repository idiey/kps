<?php

namespace Database\Seeders;

use App\Models\Kps\Site;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',
            'view-roles',
            'create-roles',
            'edit-roles',
            'delete-roles',
            'assign-permissions',
            'kps.view',
            'kps.manage_sites',
            'kps.manage_peneroka',
            'kps.manage_hutang',
            'kps.manage_potongan',
            'kps.view_reports',
            'kps.approve_month',
        ];

        Permission::query()
            ->whereNotIn('name', $permissions)
            ->get()
            ->each(function (Permission $permission): void {
                $permission->roles()->detach();
                $permission->delete();
            });

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        $roleDefinitions = [
            'pentadbiran' => [
                'description' => 'Global KPS administration',
                'color' => 'red',
                'is_system_role' => true,
                'is_active' => true,
                'permissions' => $permissions,
            ],
            'company_admin' => [
                'description' => 'HQ operations oversight for all KPS sites',
                'color' => 'blue',
                'is_system_role' => true,
                'is_active' => true,
                'permissions' => [
                    'kps.view',
                    'kps.manage_sites',
                    'kps.view_reports',
                    'kps.approve_month',
                ],
            ],
            'site_admin' => [
                'description' => 'Site-level KPS administration',
                'color' => 'cyan',
                'is_system_role' => true,
                'is_active' => true,
                'permissions' => [
                    'kps.view',
                    'kps.manage_peneroka',
                    'kps.manage_hutang',
                    'kps.manage_potongan',
                    'kps.view_reports',
                    'kps.approve_month',
                ],
            ],
            'staff' => [
                'description' => 'Basic KPS site access',
                'color' => 'slate',
                'is_system_role' => true,
                'is_active' => true,
                'permissions' => [
                    'kps.view',
                ],
            ],
        ];

        foreach ($roleDefinitions as $name => $definition) {
            $role = Role::updateOrCreate(
                ['name' => $name, 'guard_name' => 'web'],
                [
                    'description' => $definition['description'],
                    'color' => $definition['color'],
                    'is_system_role' => $definition['is_system_role'],
                    'is_active' => $definition['is_active'],
                ],
            );

            $role->syncPermissions($definition['permissions']);
        }

        $this->normalizeAssignedUserRoles();

        Role::query()
            ->whereDoesntHave('users')
            ->whereDoesntHave('permissions')
            ->get()
            ->each(function (Role $role): void {
                $role->delete();
            });

        $this->command->info('Roles and permissions seeded successfully!');
    }

    protected function normalizeAssignedUserRoles(): void
    {
        $users = User::with('kpsSites')->get();

        foreach ($users as $user) {
            if ($user->hasRole(['pentadbiran', 'company_admin'])) {
                continue;
            }

            if ($user->kpsSites->contains(fn (Site $site) => $site->pivot?->role === 'site_admin')) {
                $user->syncRoles(['site_admin']);

                continue;
            }

            if ($user->kpsSites->isNotEmpty()) {
                $user->syncRoles(['staff']);
            }
        }
    }
}
