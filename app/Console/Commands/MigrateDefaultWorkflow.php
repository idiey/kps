<?php

namespace App\Console\Commands;

use App\Enums\JobStatus;
use App\Models\Workflow\Workflow;
use App\Models\Workflow\WorkflowStatus;
use App\Models\Workflow\WorkflowTransition;
use Illuminate\Console\Command;

class MigrateDefaultWorkflow extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'workflow:migrate-default
                            {--force : Force migration without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create default workflow from JobStatus enum';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if (!$this->option('force')) {
            if (!$this->confirm('This will create a default workflow from JobStatus enum. Continue?')) {
                $this->info('Migration cancelled.');
                return Command::SUCCESS;
            }
        }

        $this->info('Creating default workflow...');

        // Step 1: Create workflow
        $workflow = $this->createWorkflow();

        // Step 2: Create statuses
        $this->info('Creating workflow statuses...');
        $statuses = $this->createStatuses($workflow);

        // Step 3: Create transitions
        $this->info('Creating workflow transitions...');
        $this->createTransitions($workflow, $statuses);

        $this->info('✓ Default workflow created successfully!');
        $this->info("  Workflow ID: {$workflow->id}");
        $this->info("  Statuses: " . count($statuses));

        return Command::SUCCESS;
    }

    /**
     * Create the default workflow.
     */
    protected function createWorkflow(): Workflow
    {
        $workflow = Workflow::updateOrCreate(
            ['code' => 'kew-pa-10-default'],
            [
                'name' => 'KEW.PA-10 Default Workflow',
                'description' => 'Default workflow migrated from JobStatus enum. Handles standard workshop job lifecycle.',
                'is_active' => true,
                'is_default' => true,
                'metadata' => [
                    'migrated_from' => 'JobStatus enum',
                    'migrated_at' => now()->toIso8601String(),
                ],
            ]
        );

        $this->line("  ✓ Created workflow: {$workflow->name}");

        return $workflow;
    }

    /**
     * Create workflow statuses from JobStatus enum.
     */
    protected function createStatuses(Workflow $workflow): array
    {
        $statuses = [];
        $order = 0;

        foreach (JobStatus::cases() as $status) {
            $workflowStatus = WorkflowStatus::updateOrCreate(
                [
                    'workflow_id' => $workflow->id,
                    'code' => $status->value,
                ],
                [
                    'name' => $status->label(),
                    'description' => "Status: {$status->label()}",
                    'color' => $status->color(),
                    'icon' => $this->getIconForStatus($status),
                    'is_initial' => $status === JobStatus::NEW,
                    'is_final' => in_array($status, [JobStatus::INVOICED, JobStatus::CANCELLED]),
                    'display_order' => $order++,
                ]
            );

            $statuses[$status->value] = $workflowStatus;
            $this->line("  ✓ Created status: {$workflowStatus->name}");
        }

        return $statuses;
    }

    /**
     * Create workflow transitions based on JobStatus allowedTransitions.
     */
    protected function createTransitions(Workflow $workflow, array $statuses): void
    {
        $order = 0;

        foreach (JobStatus::cases() as $fromStatus) {
            $allowedTransitions = $fromStatus->allowedTransitions();

            foreach ($allowedTransitions as $toStatusValue) {
                $toStatus = JobStatus::from($toStatusValue);

                if (!isset($statuses[$fromStatus->value]) || !isset($statuses[$toStatus->value])) {
                    continue;
                }

                $transition = WorkflowTransition::updateOrCreate(
                    [
                        'workflow_id' => $workflow->id,
                        'from_status_id' => $statuses[$fromStatus->value]->id,
                        'to_status_id' => $statuses[$toStatus->value]->id,
                    ],
                    [
                        'name' => $this->getTransitionName($fromStatus, $toStatus),
                        'description' => "Transition from {$fromStatus->label()} to {$toStatus->label()}",
                        'button_label' => $this->getButtonLabel($toStatus),
                        'button_color' => $this->getButtonColor($toStatus),
                        'requires_comment' => $this->requiresComment($fromStatus, $toStatus),
                        'is_active' => true,
                        'display_order' => $order++,
                        'allowed_roles' => $this->getAllowedRoles($fromStatus, $toStatus),
                    ]
                );

                $this->line("  ✓ Created transition: {$fromStatus->label()} → {$toStatus->label()}");
            }
        }
    }

    /**
     * Get icon name for a status.
     */
    protected function getIconForStatus(JobStatus $status): string
    {
        return match ($status) {
            JobStatus::NEW => 'document-plus',
            JobStatus::PENDING_KEW_PA_10_VERIFICATION => 'clock',
            JobStatus::KEW_PA_10_VERIFIED => 'check-circle',
            JobStatus::PENDING_INSPECTION => 'clipboard-document-list',
            JobStatus::INSPECTION_IN_PROGRESS => 'magnifying-glass',
            JobStatus::INSPECTION_APPROVED => 'check-badge',
            JobStatus::REPAIR_IN_PROGRESS => 'wrench-screwdriver',
            JobStatus::PENDING_REVIEW => 'document-magnifying-glass',
            JobStatus::COMPLETED => 'check-circle',
            JobStatus::PENDING_KEW_PA_10_RETURN => 'arrow-uturn-left',
            JobStatus::KEW_PA_10_RETURNED => 'archive-box',
            JobStatus::INVOICED => 'currency-dollar',
            JobStatus::CANCELLED => 'x-circle',
            JobStatus::ON_HOLD => 'pause-circle',
        };
    }

    /**
     * Get transition name.
     */
    protected function getTransitionName(JobStatus $from, JobStatus $to): string
    {
        return match ($to) {
            JobStatus::PENDING_KEW_PA_10_VERIFICATION => 'Submit for Verification',
            JobStatus::KEW_PA_10_VERIFIED => 'Verify KEW.PA-10',
            JobStatus::PENDING_INSPECTION => 'Request Inspection',
            JobStatus::INSPECTION_IN_PROGRESS => 'Start Inspection',
            JobStatus::INSPECTION_APPROVED => 'Approve Inspection',
            JobStatus::REPAIR_IN_PROGRESS => 'Start Repair',
            JobStatus::PENDING_REVIEW => 'Submit for Review',
            JobStatus::COMPLETED => 'Mark as Completed',
            JobStatus::PENDING_KEW_PA_10_RETURN => 'Prepare Return',
            JobStatus::KEW_PA_10_RETURNED => 'Mark as Returned',
            JobStatus::INVOICED => 'Generate Invoice',
            JobStatus::CANCELLED => 'Cancel Job',
            JobStatus::ON_HOLD => 'Put On Hold',
            default => "Move to {$to->label()}",
        };
    }

    /**
     * Get button label for transition.
     */
    protected function getButtonLabel(JobStatus $to): string
    {
        return $this->getTransitionName(JobStatus::NEW, $to);
    }

    /**
     * Get button color for transition.
     */
    protected function getButtonColor(JobStatus $to): string
    {
        return match ($to) {
            JobStatus::INSPECTION_APPROVED, JobStatus::COMPLETED, JobStatus::INVOICED => 'green',
            JobStatus::CANCELLED => 'red',
            JobStatus::ON_HOLD => 'yellow',
            default => 'blue',
        };
    }

    /**
     * Check if transition requires comment.
     */
    protected function requiresComment(JobStatus $from, JobStatus $to): bool
    {
        return in_array($to, [JobStatus::CANCELLED, JobStatus::ON_HOLD]);
    }

    /**
     * Get allowed roles for transition.
     */
    protected function getAllowedRoles(JobStatus $from, JobStatus $to): ?array
    {
        // Admin can perform all transitions
        $allowedRoles = [1]; // Assuming pentadbiran role ID is 1

        // Add role-specific permissions
        if ($to === JobStatus::INSPECTION_IN_PROGRESS || $to === JobStatus::INSPECTION_APPROVED) {
            $allowedRoles[] = 3; // pemeriksa
        }

        if ($to === JobStatus::REPAIR_IN_PROGRESS || $to === JobStatus::COMPLETED) {
            $allowedRoles[] = 5; // juruteknik
        }

        if ($to === JobStatus::INVOICED) {
            $allowedRoles[] = 2; // penyelia
        }

        return count($allowedRoles) > 1 ? $allowedRoles : null;
    }
}
