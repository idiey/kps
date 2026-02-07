<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use App\Models\Workflow\Workflow;
use App\Models\WorkshopJob;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class StrictRoleWorkflowJobDemoSeeder extends Seeder
{
    public function run(): void
    {
        $roles = Role::where('is_active', true)->get(['id', 'name']);
        $roleIds = $roles->pluck('id')->map(fn ($id) => (int) $id)->values()->toArray();

        $adminRole = $roles->firstWhere('name', 'pentadbiran');
        $technicianRole = $roles->firstWhere('name', 'juruteknik');
        $inspectorRole = $roles->firstWhere('name', 'pemeriksa');

        $admin = $this->ensureUser('admin@demo.test', 'Admin Demo', 'pentadbiran');
        $technician = $this->ensureUser('technician@demo.test', 'Technician Demo', 'juruteknik');
        $inspector = $this->ensureUser('inspector@demo.test', 'Inspector Demo', 'pemeriksa');

        if ($adminRole) {
            $admin->syncRoles([$adminRole->name]);
        }
        if ($technicianRole) {
            $technician->syncRoles([$technicianRole->name]);
        }
        if ($inspectorRole) {
            $inspector->syncRoles([$inspectorRole->name]);
        }

        if (Customer::count() === 0) {
            Customer::factory()->count(5)->create();
        }

        $workflowAll = $this->createWorkflowWithGraph('All Roles Workflow', 'wf-all', $roleIds);
        $workflowTech = $technicianRole ? $this->createWorkflowWithGraph('Technician Workflow', 'wf-tech', [$technicianRole->id]) : null;
        $workflowInspector = $inspectorRole ? $this->createWorkflowWithGraph('Inspector Workflow', 'wf-inspector', [$inspectorRole->id]) : null;

        $this->createJobsForWorkflow($workflowAll, $technician->id);
        if ($workflowTech) {
            $this->createJobsForWorkflow($workflowTech, $technician->id);
        }
        if ($workflowInspector) {
            $this->createJobsForWorkflow($workflowInspector, $technician->id);
        }
    }

    protected function ensureUser(string $email, string $name, string $role): User
    {
        return User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make('password'),
                'role' => $role,
                'email_verified_at' => now(),
            ]
        );
    }

    /**
     * @param array<int, int> $allowedRoleIds
     */
    protected function createWorkflowWithGraph(string $name, string $code, array $allowedRoleIds): Workflow
    {
        $allowedRoleIds = array_values(array_unique(array_map('intval', $allowedRoleIds)));

        $workflow = Workflow::firstOrCreate(
            ['code' => $code],
            [
                'name' => $name,
                'description' => null,
                'is_active' => true,
                'is_default' => false,
                'allowed_roles' => $allowedRoleIds,
            ]
        );

        $workflow->update(['allowed_roles' => $allowedRoleIds]);

        if ($workflow->statuses()->count() > 0) {
            return $workflow;
        }

        $s1 = $workflow->statuses()->create([
            'name' => 'New',
            'code' => 'new',
            'is_initial' => true,
            'is_final' => false,
            'display_order' => 0,
        ]);

        $s2 = $workflow->statuses()->create([
            'name' => 'In Progress',
            'code' => 'in_progress',
            'is_initial' => false,
            'is_final' => false,
            'display_order' => 1,
        ]);

        $s3 = $workflow->statuses()->create([
            'name' => 'Done',
            'code' => 'done',
            'is_initial' => false,
            'is_final' => true,
            'display_order' => 2,
        ]);

        $workflow->transitions()->create([
            'name' => 'Start',
            'from_status_id' => $s1->id,
            'to_status_id' => $s2->id,
            'allowed_roles' => $allowedRoleIds,
            'is_active' => true,
            'display_order' => 0,
        ]);

        $workflow->transitions()->create([
            'name' => 'Complete',
            'from_status_id' => $s2->id,
            'to_status_id' => $s3->id,
            'allowed_roles' => $allowedRoleIds,
            'is_active' => true,
            'display_order' => 1,
        ]);

        return $workflow;
    }

    protected function createJobsForWorkflow(Workflow $workflow, ?int $assignedTo): void
    {
        $initialStatus = $workflow->initialStatus();

        WorkshopJob::factory()->count(3)->create([
            'workflow_id' => $workflow->id,
            'current_workflow_status_id' => $initialStatus?->id,
            'assigned_to' => $assignedTo,
        ]);
    }
}

