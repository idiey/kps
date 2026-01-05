<?php

namespace Database\Seeders;

use App\Models\Workflow\Workflow;
use App\Models\Workflow\WorkflowStatus;
use App\Models\Workflow\WorkflowTransition;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class InternalInspectionWorkflowSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ensure Roles Exist
        $inspectorRole = Role::firstOrCreate(['name' => 'pemeriksa', 'guard_name' => 'web'], ['description' => 'Inspector']);
        $supervisorRole = Role::firstOrCreate(['name' => 'penyelia', 'guard_name' => 'web'], ['description' => 'Supervisor']);
        $adminRole = Role::firstOrCreate(['name' => 'pentadbiran', 'guard_name' => 'web'], ['description' => 'Admin Officer']);
        $approverRole = Role::firstOrCreate(['name' => 'pelulus', 'guard_name' => 'web'], ['description' => 'Approver']);
        $technicianRole = Role::firstOrCreate(['name' => 'juruteknik', 'guard_name' => 'web'], ['description' => 'Technician']);

        // 2. Create Workflow
        $workflow = Workflow::firstOrCreate(
            ['code' => 'internal-inspection-kew-pa-10'],
            [
                'name' => 'Internal Inspection & KEW.PA-10',
                'description' => 'Workflow for internal asset inspections generating KEW.PA-10 forms.',
                'is_active' => true,
                'is_default' => false,
                'metadata' => [
                    'type' => 'inspection',
                    'form' => 'KEW.PA-10'
                ]
            ]
        );

        $this->command->info("Created Workflow: {$workflow->name}");

        // 3. Create Statuses
        $statuses = [
            [
                'name' => 'Inspection In Progress',
                'code' => 'inspection_in_progress',
                'description' => 'Inspector conducting physical inspection.',
                'color' => '#f59e0b', // Amber 500
                'is_initial' => true,
                'is_final' => false,
                'display_order' => 10,
            ],
            [
                'name' => 'Inspection Submitted',
                'code' => 'inspection_submitted',
                'description' => 'Inspection report submitted for review.',
                'color' => '#3b82f6', // Blue 500
                'is_initial' => false,
                'is_final' => false,
                'display_order' => 20,
            ],
            [
                'name' => 'Inspection Verified',
                'code' => 'inspection_verified',
                'description' => 'Supervisor verified inspection findings.',
                'color' => '#8b5cf6', // Violet 500
                'is_initial' => false,
                'is_final' => false,
                'display_order' => 30,
            ],
            [
                'name' => 'KEW.PA-10 Generated',
                'code' => 'kew_pa_10_generated',
                'description' => 'Admin generated KEW.PA-10 form from findings.',
                'color' => '#6366f1', // Indigo 500
                'is_initial' => false,
                'is_final' => false,
                'display_order' => 40,
            ],
            [
                'name' => 'Pending Approval',
                'code' => 'pending_approval',
                'description' => 'Job awaiting budget/technical approval.',
                'color' => '#f97316', // Orange 500
                'is_initial' => false,
                'is_final' => false,
                'display_order' => 50,
            ],
            [
                'name' => 'Approved',
                'code' => 'approved',
                'description' => 'Job approved for execution.',
                'color' => '#22c55e', // Green 500
                'is_initial' => false,
                'is_final' => false,
                'display_order' => 60,
            ],
            [
                'name' => 'Repair In Progress',
                'code' => 'repair_in_progress_int',
                'description' => 'Technician performing repair work.',
                'color' => '#06b6d4', // Cyan 500
                'is_initial' => false,
                'is_final' => false,
                'display_order' => 70,
            ],
            [
                'name' => 'Repair Completed',
                'code' => 'repair_completed_int',
                'description' => 'Repair work finished.',
                'color' => '#14b8a6', // Teal 500
                'is_initial' => false,
                'is_final' => false,
                'display_order' => 80,
            ],
            [
                'name' => 'Work Verified',
                'code' => 'work_verified',
                'description' => 'Supervisor verified completed work.',
                'color' => '#10b981', // Emerald 500
                'is_initial' => false,
                'is_final' => false,
                'display_order' => 90,
            ],
            [
                'name' => 'Closed',
                'code' => 'closed_int',
                'description' => 'Work order closed.',
                'color' => '#1e293b', // Slate 800
                'is_initial' => false,
                'is_final' => true,
                'display_order' => 100,
            ],
        ];

        $statusMap = [];
        foreach ($statuses as $statusData) {
            $status = WorkflowStatus::updateOrCreate(
                ['workflow_id' => $workflow->id, 'code' => $statusData['code']],
                array_merge($statusData, ['workflow_id' => $workflow->id])
            );
            $statusMap[$statusData['code']] = $status->id;
        }

        $this->command->info("Created " . count($statuses) . " statuses.");

        // 4. Create Transitions
        $transitions = [
            [
                'from' => 'inspection_in_progress',
                'to' => 'inspection_submitted',
                'name' => 'Submit Inspection',
                'description' => 'Submit inspection findings for review.',
                'roles' => [$inspectorRole->id],
            ],
            [
                'from' => 'inspection_submitted',
                'to' => 'inspection_verified',
                'name' => 'Verify Findings',
                'description' => 'Supervisor verifies inspection findings.',
                'roles' => [$supervisorRole->id],
            ],
            [
                'from' => 'inspection_verified',
                'to' => 'kew_pa_10_generated',
                'name' => 'Generate KEW.PA-10',
                'description' => 'Generate official KEW.PA-10 form.',
                'roles' => [$adminRole->id],
            ],
            [
                'from' => 'kew_pa_10_generated',
                'to' => 'pending_approval',
                'name' => 'Submit for Approval',
                'description' => 'Create job and submit for approval.',
                'roles' => [$adminRole->id],
            ],
            [
                'from' => 'pending_approval',
                'to' => 'approved',
                'name' => 'Approve Job',
                'description' => 'Approve job and budget allocation.',
                'roles' => [$approverRole->id],
            ],
            [
                'from' => 'approved',
                'to' => 'repair_in_progress_int',
                'name' => 'Start Repair',
                'description' => 'Begin repair work.',
                'roles' => [$technicianRole->id],
            ],
            [
                'from' => 'repair_in_progress_int',
                'to' => 'repair_completed_int',
                'name' => 'Complete Repair',
                'description' => 'Mark repair work as finished.',
                'roles' => [$technicianRole->id],
            ],
            [
                'from' => 'repair_completed_int',
                'to' => 'work_verified',
                'name' => 'Verify Work',
                'description' => 'Verify the completed repair work.',
                'roles' => [$supervisorRole->id],
            ],
            [
                'from' => 'work_verified',
                'to' => 'closed_int',
                'name' => 'Close Work Order',
                'description' => 'Close the work order.',
                'roles' => [$supervisorRole->id],
            ],
        ];

        foreach ($transitions as $t) {
            WorkflowTransition::updateOrCreate(
                [
                    'workflow_id' => $workflow->id,
                    'from_status_id' => $statusMap[$t['from']],
                    'to_status_id' => $statusMap[$t['to']],
                    'name' => $t['name'],
                ],
                [
                    'description' => $t['description'],
                    'allowed_roles' => $t['roles'],
                    'is_active' => true,
                ]
            );
        }

        $this->command->info("Created " . count($transitions) . " transitions.");
    }
}
