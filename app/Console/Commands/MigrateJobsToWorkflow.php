<?php

namespace App\Console\Commands;

use App\Models\Template\JobTemplate;
use App\Models\Workflow\Workflow;
use App\Models\Workflow\WorkflowStatus;
use App\Models\WorkshopJob;
use Illuminate\Console\Command;

class MigrateJobsToWorkflow extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'workflow:migrate-jobs
                            {--batch=100 : Number of jobs to process per batch}
                            {--force : Force migration without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate existing jobs to use default template and workflow';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // Get default template and workflow
        $template = JobTemplate::where('code', 'standard-job')->first();
        $workflow = Workflow::where('code', 'kew-pa-10-default')->first();

        if (!$template) {
            $this->error('Default template not found. Please run: php artisan workflow:migrate-template');
            return Command::FAILURE;
        }

        if (!$workflow) {
            $this->error('Default workflow not found. Please run: php artisan workflow:migrate-default');
            return Command::FAILURE;
        }

        // Count jobs that need migration
        $totalJobs = WorkshopJob::whereNull('template_id')
            ->orWhereNull('workflow_id')
            ->count();

        if ($totalJobs === 0) {
            $this->info('No jobs need migration.');
            return Command::SUCCESS;
        }

        $this->info("Found {$totalJobs} jobs to migrate.");

        if (!$this->option('force')) {
            if (!$this->confirm("Migrate {$totalJobs} jobs to use template and workflow?")) {
                $this->info('Migration cancelled.');
                return Command::SUCCESS;
            }
        }

        $this->info('Starting job migration...');

        $stats = $this->migrateJobs($template, $workflow);

        $this->newLine();
        $this->info('✓ Job migration completed!');
        $this->table(
            ['Status', 'Count'],
            [
                ['Migrated', $stats['migrated']],
                ['Status Mapped', $stats['status_mapped']],
                ['Skipped', $stats['skipped']],
                ['Errors', $stats['errors']],
            ]
        );

        return Command::SUCCESS;
    }

    /**
     * Migrate jobs to workflow system.
     */
    protected function migrateJobs(JobTemplate $template, Workflow $workflow): array
    {
        $batchSize = (int) $this->option('batch');

        $stats = [
            'migrated' => 0,
            'status_mapped' => 0,
            'skipped' => 0,
            'errors' => 0,
        ];

        // Get workflow statuses for mapping
        $workflowStatuses = WorkflowStatus::where('workflow_id', $workflow->id)
            ->get()
            ->keyBy('code');

        // Process jobs in batches
        WorkshopJob::whereNull('template_id')
            ->orWhereNull('workflow_id')
            ->chunkById($batchSize, function ($jobs) use ($template, $workflow, $workflowStatuses, &$stats) {
                $bar = $this->output->createProgressBar($jobs->count());
                $bar->start();

                foreach ($jobs as $job) {
                    try {
                        // Skip if already migrated
                        if ($job->template_id && $job->workflow_id) {
                            $stats['skipped']++;
                            $bar->advance();
                            continue;
                        }

                        // Set template and workflow
                        $job->template_id = $template->id;
                        $job->workflow_id = $workflow->id;

                        // Map current status to workflow status
                        if ($job->status && !$job->current_workflow_status_id) {
                            $statusCode = $job->status->value;

                            if (isset($workflowStatuses[$statusCode])) {
                                $job->current_workflow_status_id = $workflowStatuses[$statusCode]->id;
                                $stats['status_mapped']++;
                            }
                        }

                        $job->save();
                        $stats['migrated']++;
                    } catch (\Exception $e) {
                        $this->error("\n  ✗ Error migrating job {$job->id}: {$e->getMessage()}");
                        $stats['errors']++;
                    }

                    $bar->advance();
                }

                $bar->finish();
                $this->newLine();
            });

        return $stats;
    }
}
