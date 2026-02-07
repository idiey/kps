<?php

namespace App\Console\Commands;

use App\Enums\JobPriority;
use App\Enums\JobStatus;
use App\Models\Customer;
use App\Models\User;
use App\Models\Workflow\Workflow;
use App\Models\Workflow\WorkflowStatus;
use App\Services\Job\DynamicJobService;
use App\Services\Workflow\WorkflowExecutor;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class SetupAlabalaWorkflow extends Command
{
    protected $signature = 'workshop:setup-alabala-workflow {--verify : Create demo users/job and verify transition gating}';

    protected $description = 'Setup workflow "alabala" with role-gated transitions: pentadbiran and pelulus';

    public function handle(WorkflowExecutor $workflowExecutor, DynamicJobService $jobService): int
    {
        $pentadbirRole = Role::firstOrCreate(
            ['name' => 'pentadbiran', 'guard_name' => 'web'],
            ['is_active' => true, 'description' => 'Pentadbir']
        );

        $pelulusRole = Role::firstOrCreate(
            ['name' => 'pelulus', 'guard_name' => 'web'],
            ['is_active' => true, 'description' => 'Pelulus']
        );

        $workflow = Workflow::updateOrCreate(
            ['code' => 'alabala'],
            [
                'name' => 'alabala',
                'description' => 'Demo workflow for role-gated transitions',
                'is_active' => true,
                'is_default' => false,
                'allowed_roles' => [$pentadbirRole->id, $pelulusRole->id],
            ]
        );

        $start = WorkflowStatus::updateOrCreate(
            ['workflow_id' => $workflow->id, 'code' => 'start'],
            [
                'workflow_id' => $workflow->id,
                'name' => 'Start',
                'description' => 'Start status',
                'color' => '#64748b',
                'is_initial' => true,
                'is_final' => false,
                'display_order' => 10,
                'required_template_id' => null,
            ]
        );

        $inprogress = WorkflowStatus::updateOrCreate(
            ['workflow_id' => $workflow->id, 'code' => 'inprogress'],
            [
                'workflow_id' => $workflow->id,
                'name' => 'In Progress',
                'description' => 'In progress status',
                'color' => '#3b82f6',
                'is_initial' => false,
                'is_final' => false,
                'display_order' => 20,
                'required_template_id' => null,
            ]
        );

        $end = WorkflowStatus::updateOrCreate(
            ['workflow_id' => $workflow->id, 'code' => 'end'],
            [
                'workflow_id' => $workflow->id,
                'name' => 'End',
                'description' => 'End status',
                'color' => '#22c55e',
                'is_initial' => false,
                'is_final' => true,
                'display_order' => 30,
                'required_template_id' => null,
            ]
        );

        $requiredStatusIds = [$start->id, $inprogress->id, $end->id];

        $workflow->statuses()
            ->whereNotIn('id', $requiredStatusIds)
            ->update(['is_initial' => false, 'is_final' => false]);

        if ($start->id !== $inprogress->id) {
            $t1 = $workflow->transitions()->updateOrCreate(
                ['from_status_id' => $start->id, 'to_status_id' => $inprogress->id],
                [
                    'name' => 'Start → In Progress',
                    'description' => 'Move from start to inprogress',
                    'allowed_roles' => [$pentadbirRole->id],
                    'requires_permission' => null,
                    'conditions' => null,
                    'actions' => null,
                    'requires_comment' => false,
                    'is_active' => true,
                    'display_order' => 10,
                ]
            );
        }

        if ($inprogress->id !== $end->id) {
            $t2 = $workflow->transitions()->updateOrCreate(
                ['from_status_id' => $inprogress->id, 'to_status_id' => $end->id],
                [
                    'name' => 'In Progress → End',
                    'description' => 'Move from inprogress to end',
                    'allowed_roles' => [$pelulusRole->id],
                    'requires_permission' => null,
                    'conditions' => null,
                    'actions' => null,
                    'requires_comment' => false,
                    'is_active' => true,
                    'display_order' => 20,
                ]
            );
        }

        $workflow->transitions()
            ->whereNot(function ($q) use ($start, $inprogress, $end) {
                $q->where('from_status_id', $start->id)->where('to_status_id', $inprogress->id);
            })
            ->whereNot(function ($q) use ($start, $inprogress, $end) {
                $q->where('from_status_id', $inprogress->id)->where('to_status_id', $end->id);
            })
            ->delete();

        $workflow->statuses()
            ->whereNotIn('id', $requiredStatusIds)
            ->get()
            ->each(function (WorkflowStatus $status) {
                if ($status->jobs()->count() > 0) {
                    return;
                }
                if ($status->transitionsFrom()->count() > 0 || $status->transitionsTo()->count() > 0) {
                    return;
                }
                $status->delete();
            });

        $this->info('Workflow created/updated:');
        $this->line("  workflow_id={$workflow->id} code={$workflow->code} name={$workflow->name}");
        $this->line("  roles: pentadbiran={$pentadbirRole->id}, pelulus={$pelulusRole->id}");
        $this->line("  statuses: start={$start->id}, inprogress={$inprogress->id}, end={$end->id}");
        $this->newLine();
        $this->info('Transitions:');
        $workflow->load(['transitions.fromStatus', 'transitions.toStatus']);
        foreach ($workflow->transitions()->orderBy('display_order')->get() as $transition) {
            $fromCode = $transition->fromStatus?->code ?? '-';
            $toCode = $transition->toStatus?->code ?? '-';
            $this->line("  transition_id={$transition->id} {$fromCode}->{$toCode} allowed_roles=" . json_encode($transition->allowed_roles));
        }

        if (!$this->option('verify')) {
            return self::SUCCESS;
        }

        $pentadbirUser = User::firstOrCreate(
            ['email' => 'alabala-pentadbir@example.com'],
            ['name' => 'Alabala Pentadbir', 'password' => bcrypt('password'), 'role' => 'pentadbiran']
        );
        $pentadbirUser->forceFill(['role' => 'pentadbiran'])->save();
        $pentadbirUser->syncRoles([$pentadbirRole->name]);

        $pelulusUser = User::firstOrCreate(
            ['email' => 'alabala-pelulus@example.com'],
            ['name' => 'Alabala Pelulus', 'password' => bcrypt('password'), 'role' => 'pelulus']
        );
        $pelulusUser->forceFill(['role' => 'pelulus'])->save();
        $pelulusUser->syncRoles([$pelulusRole->name]);

        $customer = Customer::firstOrCreate(
            ['name' => 'Alabala Customer'],
            ['email' => 'alabala.customer@example.com', 'phone' => '0123456789']
        );

        $job = \App\Models\WorkshopJob::create([
            'job_number' => 'WJ-ALABALA-' . strtoupper(substr(uniqid(), -8)),
            'customer_id' => $customer->id,
            'title' => 'Alabala Job',
            'description' => 'Seeded for alabala transition verification',
            'status' => JobStatus::NEW->value,
            'priority' => JobPriority::MEDIUM->value,
            'workflow_id' => $workflow->id,
            'current_workflow_status_id' => $start->id,
        ]);

        $tStartToInprogress = $workflow->transitions()
            ->where('from_status_id', $start->id)
            ->where('to_status_id', $inprogress->id)
            ->first();

        $tInprogressToEnd = $workflow->transitions()
            ->where('from_status_id', $inprogress->id)
            ->where('to_status_id', $end->id)
            ->first();

        $this->newLine();
        $this->info("Verification job_id={$job->id} current_status=start");

        Auth::guard('web')->login($pentadbirUser);
        $pentadbirTransitions = $workflowExecutor->getAvailableTransitions($job->fresh());
        $this->line('As pentadbiran at start: transitions=' . $pentadbirTransitions->pluck('id')->implode(','));

        Auth::guard('web')->login($pelulusUser);
        $pelulusTransitions = $workflowExecutor->getAvailableTransitions($job->fresh());
        $this->line('As pelulus at start: transitions=' . $pelulusTransitions->pluck('id')->implode(','));

        Auth::guard('web')->login($pentadbirUser);
        $jobService->executeTransition($job->fresh(), $tStartToInprogress->id, ['notes' => 'Start to inprogress']);
        $job->refresh();
        $this->line("After pentadbiran executes t1: current_status_id={$job->current_workflow_status_id}");

        Auth::guard('web')->login($pentadbirUser);
        $pentadbirTransitions2 = $workflowExecutor->getAvailableTransitions($job->fresh());
        $this->line('As pentadbiran at inprogress: transitions=' . $pentadbirTransitions2->pluck('id')->implode(','));

        Auth::guard('web')->login($pelulusUser);
        $pelulusTransitions2 = $workflowExecutor->getAvailableTransitions($job->fresh());
        $this->line('As pelulus at inprogress: transitions=' . $pelulusTransitions2->pluck('id')->implode(','));

        Auth::guard('web')->login($pentadbirUser);
        try {
            $jobService->executeTransition($job->fresh(), $tInprogressToEnd->id, ['notes' => 'Should fail']);
            $this->error('Unexpected: pentadbiran executed inprogress->end');
        } catch (\Throwable $e) {
            $this->line('As pentadbiran executing t2: blocked as expected');
        }

        Auth::guard('web')->login($pelulusUser);
        $jobService->executeTransition($job->fresh(), $tInprogressToEnd->id, ['notes' => 'Inprogress to end']);
        $job->refresh();
        $this->line("After pelulus executes t2: current_status_id={$job->current_workflow_status_id}");

        return self::SUCCESS;
    }
}
