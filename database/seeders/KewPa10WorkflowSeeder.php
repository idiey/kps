<?php

namespace Database\Seeders;

use App\Models\Workflow\Workflow;
use App\Models\Workflow\WorkflowStatus;
use App\Models\Workflow\WorkflowTransition;
use App\Models\Workflow\WorkflowRule;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class KewPa10WorkflowSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ensure Roles Exist
        $adminRole = Role::firstOrCreate(['name' => 'pentadbiran', 'guard_name' => 'web'], ['description' => 'Admin Officer']);
        $supervisorRole = Role::firstOrCreate(['name' => 'penyelia', 'guard_name' => 'web'], ['description' => 'Supervisor']);
        $inspectorRole = Role::firstOrCreate(['name' => 'pemeriksa', 'guard_name' => 'web'], ['description' => 'Inspector']);
        $technicianRole = Role::firstOrCreate(['name' => 'juruteknik', 'guard_name' => 'web'], ['description' => 'Technician']);

        // 2. Create Workflow
        $workflow = Workflow::firstOrCreate(
            ['code' => 'kew-pa-10-external'],
            [
                'name' => 'KEW.PA-10 External Reception',
                'description' => 'Workflow for handling KEW.PA-10 forms received from government departments requesting asset repairs.',
                'is_active' => true,
                'is_default' => false,
                'metadata' => [
                    'type' => 'repair',
                    'form' => 'KEW.PA-10'
                ]
            ]
        );

        $this->command->info("Created Workflow: {$workflow->name}");

        // 3. Create Statuses
        $statuses = [
            [
                'name' => 'Received',
                'code' => 'received',
                'description' => 'KEW.PA-10 form received from external department.',
                'color' => '#64748b', // Slate 500
                'is_initial' => true,
                'is_final' => false,
                'display_order' => 10,
            ],
            [
                'name' => 'Registered',
                'code' => 'registered',
                'description' => 'Job registered in system and linked to KEW.PA-10.',
                'color' => '#3b82f6', // Blue 500
                'is_initial' => false,
                'is_final' => false,
                'display_order' => 20,
            ],
            [
                'name' => 'Under Review',
                'code' => 'under_review',
                'description' => 'Supervisor reviewing job request.',
                'color' => '#eab308', // Yellow 500
                'is_initial' => false,
                'is_final' => false,
                'display_order' => 30,
            ],
            [
                'name' => 'Inspection Assigned',
                'code' => 'inspection_assigned',
                'description' => 'Inspector assigned to validate asset condition.',
                'color' => '#f97316', // Orange 500
                'is_initial' => false,
                'is_final' => false,
                'display_order' => 40,
            ],
            [
                'name' => 'Inspected',
                'code' => 'inspected',
                'description' => 'Inspection completed and findings documented.',
                'color' => '#8b5cf6', // Violet 500
                'is_initial' => false,
                'is_final' => false,
                'display_order' => 50,
            ],
            [
                'name' => 'Approved for Repair',
                'code' => 'approved_repair',
                'description' => 'Repair approved based on inspection.',
                'color' => '#22c55e', // Green 500
                'is_initial' => false,
                'is_final' => false,
                'display_order' => 60,
            ],
            [
                'name' => 'Repair In Progress',
                'code' => 'repair_in_progress',
                'description' => 'Technician performing repair work.',
                'color' => '#06b6d4', // Cyan 500
                'is_initial' => false,
                'is_final' => false,
                'display_order' => 70,
            ],
            [
                'name' => 'Repair Completed',
                'code' => 'repair_completed',
                'description' => 'Repair work finished.',
                'color' => '#14b8a6', // Teal 500
                'is_initial' => false,
                'is_final' => false,
                'display_order' => 80,
            ],
            [
                'name' => 'Verified',
                'code' => 'verified',
                'description' => 'Supervisor verified completed work.',
                'color' => '#10b981', // Emerald 500
                'is_initial' => false,
                'is_final' => false,
                'display_order' => 90,
            ],
            [
                'name' => 'Closed',
                'code' => 'closed',
                'description' => 'Job closed and KEW.PA-10 returned.',
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
                'from' => 'received',
                'to' => 'registered',
                'name' => 'Register Job',
                'description' => 'Register the KEW.PA-10 in the system.',
                'roles' => [$adminRole->id],
            ],
            [
                'from' => 'registered',
                'to' => 'under_review',
                'name' => 'Submit for Review',
                'description' => 'Submit to supervisor for review.',
                'roles' => [$adminRole->id],
            ],
            [
                'from' => 'under_review',
                'to' => 'inspection_assigned',
                'name' => 'Assign Inspector',
                'description' => 'Assign an inspector to validate asset.',
                'roles' => [$supervisorRole->id],
            ],
            [
                'from' => 'inspection_assigned',
                'to' => 'inspected',
                'name' => 'Complete Inspection',
                'description' => 'Document findings and complete inspection.',
                'roles' => [$inspectorRole->id],
            ],
            [
                'from' => 'inspected',
                'to' => 'approved_repair',
                'name' => 'Approve Repair',
                'description' => 'Approve the asset for repair.',
                'roles' => [$inspectorRole->id],
            ],
            [
                'from' => 'approved_repair',
                'to' => 'repair_in_progress',
                'name' => 'Start Repair',
                'description' => 'Begin repair work.',
                'roles' => [$technicianRole->id],
            ],
            [
                'from' => 'repair_in_progress',
                'to' => 'repair_completed',
                'name' => 'Complete Repair',
                'description' => 'Mark repair work as finished.',
                'roles' => [$technicianRole->id],
            ],
            [
                'from' => 'repair_completed',
                'to' => 'verified',
                'name' => 'Verify Work',
                'description' => 'Verify the completed repair work.',
                'roles' => [$supervisorRole->id],
            ],
            [
                'from' => 'verified',
                'to' => 'closed',
                'name' => 'Close Job',
                'description' => 'Return KEW.PA-10 and close job.',
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

        // 5. Create Rules
        // Example Rule: High Priority Notification
        // If urgency is 'High', notify supervisor
        WorkflowRule::create([
            'workflow_id' => $workflow->id,
            'status_id' => $statusMap['registered'],
            'name' => 'High Urgency Notification',
            'description' => 'Notify supervisor immediately for high urgency jobs.',
            'rule_type' => 'notification',
            'conditions' => [
                [
                    'field' => 'priority',
                    'operator' => '=',
                    'value' => 'High'
                ]
            ],
            'actions' => [
                [
                    'type' => 'notify_role',
                    'role' => 'penyelia',
                    'message' => 'High urgency KEW.PA-10 registered: {job_number}'
                ]
            ],
            'priority' => 1,
            'is_active' => true,
        ]);
        
        $this->command->info("Created example workflow rule.");
    }
}
