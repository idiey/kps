<?php

namespace Database\Seeders;

use App\Models\Workflow\Workflow;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class BackfillWorkflowAllowedRolesSeeder extends Seeder
{
    public function run(): void
    {
        $roleIds = Role::where('is_active', true)->pluck('id')->map(fn ($id) => (int) $id)->values()->toArray();

        if (count($roleIds) === 0) {
            return;
        }

        Workflow::query()
            ->whereNull('allowed_roles')
            ->update(['allowed_roles' => $roleIds]);

        $workflows = Workflow::query()
            ->whereNotNull('allowed_roles')
            ->get(['id', 'allowed_roles']);

        foreach ($workflows as $workflow) {
            $current = is_array($workflow->allowed_roles) ? $workflow->allowed_roles : [];
            $normalized = array_values(array_unique(array_map('intval', $current)));
            if (count($normalized) === 0) {
                $workflow->update(['allowed_roles' => $roleIds]);
            } elseif ($normalized !== $current) {
                $workflow->update(['allowed_roles' => $normalized]);
            }
        }
    }
}

