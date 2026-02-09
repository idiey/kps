<?php

namespace Database\Seeders;

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
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Admin access (sidebar/navigation)
            'admin.access',

            // Workshop Job permissions
            'view-jobs',
            'create-jobs',
            'edit-jobs',
            'delete-jobs',
            'assign-jobs',
            'update-job-status',

            // Customer permissions
            'view-customers',
            'create-customers',
            'edit-customers',
            'delete-customers',

            // User management permissions
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',

            // Report permissions
            'view-reports',
            'generate-reports',
            'export-reports',

            // Analytics permissions
            'view-analytics',
            'export-analytics',

            // Note permissions
            'view-notes',
            'create-notes',
            'edit-notes',
            'delete-notes',
            'view-private-notes',

            // Approval permissions
            'approve-jobs',
            'reject-jobs',
            'inspect-jobs',

            // Role & Permission management (Admin only)
            'view-roles',
            'create-roles',
            'edit-roles',
            'delete-roles',
            'assign-permissions',

            // Asset management (Admin only)
            'view-assets',
            'create-assets',
            'edit-assets',
            'delete-assets',
            'track-asset-condition',

            // Parts Inventory management (Admin only)
            'view-inventory',
            'create-inventory',
            'edit-inventory',
            'delete-inventory',
            'adjust-stock',
            'view-stock-movements',

            // System Settings (Admin only)
            'view-settings',
            'edit-settings',

            // KPS permissions
            'kps.view',
            'kps.manage_sites',
            'kps.manage_peneroka',
            'kps.manage_hutang',
            'kps.manage_potongan',
            'kps.view_reports',
            'kps.approve_month',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions

        // Admin - Full access
        $admin = Role::firstOrCreate(['name' => 'pentadbiran']);
        $admin->givePermissionTo(Permission::all());

        // Company Admin - Site management + analytics only (no job/customer creation)
        $companyAdmin = Role::firstOrCreate(['name' => 'company_admin']);
        $companyAdmin->givePermissionTo([
            'admin.access',
            // Admin modules shown in AppSidebar
            'view-users',
            'create-users',
            'edit-users',
            'view-roles',
            'view-reports',
            'generate-reports',
            'export-reports',
            'view-assets',
            'view-inventory',
            'view-stock-movements',
            'view-settings',
            // Analytics module
            'view-analytics',
            'export-analytics',
            // KPS HQ permissions
            'kps.view',
            'kps.manage_sites',
            'kps.view_reports',
            'kps.approve_month',
        ]);

        // Supervisor - Can manage jobs and assign work
        $supervisor = Role::firstOrCreate(['name' => 'penyelia']);
        $supervisor->givePermissionTo([
            'view-jobs',
            'create-jobs',
            'edit-jobs',
            'assign-jobs',
            'update-job-status',
            'view-customers',
            'create-customers',
            'edit-customers',
            'view-notes',
            'create-notes',
            'edit-notes',
            'view-private-notes',
            'view-reports',
            'generate-reports',
            'view-analytics',
            // KPS site permissions
            'kps.view',
            'kps.manage_peneroka',
            'kps.manage_hutang',
            'kps.manage_potongan',
            'kps.view_reports',
        ]);

        // Technician - Can work on assigned jobs
        $technician = Role::firstOrCreate(['name' => 'juruteknik']);
        $technician->givePermissionTo([
            'view-jobs',
            'update-job-status',
            'view-customers',
            'view-notes',
            'create-notes',
            'edit-notes',
        ]);

        // Inspector - Can inspect and verify work
        $inspector = Role::firstOrCreate(['name' => 'pemeriksa']);
        $inspector->givePermissionTo([
            'view-jobs',
            'inspect-jobs',
            'view-customers',
            'view-notes',
            'create-notes',
            'view-private-notes',
            'view-reports',
            'view-analytics',
        ]);

        // Approver - Can approve/reject jobs
        $approver = Role::firstOrCreate(['name' => 'pelulus']);
        $approver->givePermissionTo([
            'view-jobs',
            'approve-jobs',
            'reject-jobs',
            'view-customers',
            'view-notes',
            'view-private-notes',
            'view-reports',
            'generate-reports',
            'export-reports',
            'view-analytics',
        ]);

        // Front Desk - Can create jobs and manage customers (walk-in service)
        $frontdesk = Role::firstOrCreate(['name' => 'kaunter']);
        $frontdesk->givePermissionTo([
            'view-jobs',
            'create-jobs',
            'edit-jobs',
            'view-customers',
            'create-customers',
            'edit-customers',
            'view-notes',
            'create-notes',
            // KPS minimal access
            'kps.view',
        ]);

        $this->command->info('Roles and permissions seeded successfully!');
    }
}
