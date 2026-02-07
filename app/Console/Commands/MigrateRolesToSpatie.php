<?php

namespace App\Console\Commands;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

class MigrateRolesToSpatie extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'workflow:migrate-roles
                            {--force : Force migration without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate UserRole enum to Spatie roles and assign roles to users';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if (!$this->option('force')) {
            if (!$this->confirm('This will migrate all user roles from enum to Spatie. Continue?')) {
                $this->info('Migration cancelled.');
                return Command::SUCCESS;
            }
        }

        $this->info('Starting role migration...');

        // Step 1: Create roles from UserRole enum
        $this->info('Creating roles from UserRole enum...');
        $this->createRolesFromEnum();

        // Step 2: Assign roles to existing users
        $this->info('Assigning roles to existing users...');
        $this->assignRolesToUsers();

        $this->info('✓ Role migration completed successfully!');

        return Command::SUCCESS;
    }

    /**
     * Create Spatie roles from UserRole enum.
     */
    protected function createRolesFromEnum(): void
    {
        $roles = [
            [
                'name' => UserRole::PENTADBIRAN->value,
                'description' => 'Admin Officer - Manages KEW.PA-10 forms, workflows, and system administration',
                'color' => 'red',
                'is_system_role' => true,
                'is_active' => true,
            ],
            [
                'name' => UserRole::COMPANY_ADMIN->value,
                'description' => 'Company Admin - Manages company sites and site admins, and monitors analytics',
                'color' => 'teal',
                'is_system_role' => true,
                'is_active' => true,
            ],
            [
                'name' => UserRole::PENYELIA->value,
                'description' => 'Supervisor - Reviews and approves inspection and completion reports',
                'color' => 'blue',
                'is_system_role' => true,
                'is_active' => true,
            ],
            [
                'name' => UserRole::PEMERIKSA->value,
                'description' => 'Inspector - Conducts inspections and creates inspection reports',
                'color' => 'green',
                'is_system_role' => true,
                'is_active' => true,
            ],
            [
                'name' => UserRole::PELULUS->value,
                'description' => 'Approver - Final approval authority for jobs and reports',
                'color' => 'purple',
                'is_system_role' => true,
                'is_active' => true,
            ],
            [
                'name' => UserRole::JURUTEKNIK->value,
                'description' => 'Technician - Performs repairs and creates completion reports',
                'color' => 'orange',
                'is_system_role' => true,
                'is_active' => true,
            ],
        ];

        foreach ($roles as $roleData) {
            $role = Role::firstOrCreate(
                ['name' => $roleData['name']],
                $roleData
            );

            $this->line("  ✓ Created/Updated role: {$role->name}");
        }
    }

    /**
     * Assign Spatie roles to existing users based on their enum role.
     */
    protected function assignRolesToUsers(): void
    {
        if (!Schema::hasColumn('users', 'role')) {
            $this->warn('users.role column not found; skipping legacy role migration.');
            return;
        }

        $users = User::all();
        $bar = $this->output->createProgressBar($users->count());
        $bar->start();

        $stats = [
            'assigned' => 0,
            'skipped' => 0,
            'errors' => 0,
        ];

        foreach ($users as $user) {
            try {
                // Skip if user doesn't have a role value
                if (!$user->role) {
                    $stats['skipped']++;
                    $bar->advance();
                    continue;
                }

                // Get the role name from enum or string
                $roleName = $user->role instanceof UserRole ? $user->role->value : $user->role;

                // Check if role exists in Spatie
                $role = Role::where('name', $roleName)->first();

                if (!$role) {
                    $this->error("\n  ✗ Role not found: {$roleName} for user {$user->email}");
                    $stats['errors']++;
                    $bar->advance();
                    continue;
                }

                // Assign role if not already assigned
                if (!$user->hasRole($roleName)) {
                    $user->assignRole($role);
                    $stats['assigned']++;
                } else {
                    $stats['skipped']++;
                }
            } catch (\Exception $e) {
                $this->error("\n  ✗ Error assigning role to user {$user->email}: {$e->getMessage()}");
                $stats['errors']++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->table(
            ['Status', 'Count'],
            [
                ['Assigned', $stats['assigned']],
                ['Skipped', $stats['skipped']],
                ['Errors', $stats['errors']],
            ]
        );
    }
}
