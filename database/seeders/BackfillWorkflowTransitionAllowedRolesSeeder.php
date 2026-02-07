<?php

namespace Database\Seeders;

use App\Models\Workflow\Workflow;
use App\Models\Workflow\WorkflowTransition;
use Illuminate\Database\Seeder;

class BackfillWorkflowTransitionAllowedRolesSeeder extends Seeder
{
    public function run(): void
    {
        $workflows = Workflow::query()
            ->whereNotNull('allowed_roles')
            ->get(['id', 'allowed_roles']);

        foreach ($workflows as $workflow) {
            $allowed = is_array($workflow->allowed_roles) ? $workflow->allowed_roles : [];
            $allowed = array_values(array_unique(array_map('intval', $allowed)));
            if (count($allowed) === 0) {
                continue;
            }

            WorkflowTransition::query()
                ->where('workflow_id', $workflow->id)
                ->whereNull('allowed_roles')
                ->update(['allowed_roles' => $allowed]);

            $transitions = WorkflowTransition::query()
                ->where('workflow_id', $workflow->id)
                ->whereNotNull('allowed_roles')
                ->get(['id', 'allowed_roles']);

            foreach ($transitions as $transition) {
                $current = is_array($transition->allowed_roles) ? $transition->allowed_roles : [];
                $normalized = array_values(array_unique(array_map('intval', $current)));
                if (count($normalized) === 0) {
                    $transition->update(['allowed_roles' => $allowed]);
                } elseif ($normalized !== $current) {
                    $transition->update(['allowed_roles' => $normalized]);
                }
            }
        }
    }
}
