<?php

namespace Tests\Unit\Services;

use App\Enums\JobMode;
use App\Enums\JobStatus;
use App\Models\WorkshopJob;
use App\Services\JobStatusService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class JobStatusServiceTest extends TestCase
{
    use RefreshDatabase;

    protected JobStatusService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new JobStatusService();
    }

    /** @test */
    public function it_allows_valid_kew_pa_10_transitions()
    {
        $job = WorkshopJob::factory()->create([
            'job_mode' => JobMode::KEW_PA_10,
            'status' => JobStatus::NEW,
        ]);

        // NEW -> PENDING_INSPECTION
        $this->service->transitionStatus($job, JobStatus::PENDING_INSPECTION);
        $this->assertEquals(JobStatus::PENDING_INSPECTION, $job->fresh()->status);

        // PENDING_INSPECTION -> INSPECTION_IN_PROGRESS
        $this->service->transitionStatus($job, JobStatus::INSPECTION_IN_PROGRESS);
        $this->assertEquals(JobStatus::INSPECTION_IN_PROGRESS, $job->fresh()->status);
        
        // Check side effect: started_at should be set
        $this->assertNotNull($job->fresh()->started_at);
    }

    /** @test */
    public function it_denies_invalid_kew_pa_10_transitions()
    {
        $job = WorkshopJob::factory()->create([
            'job_mode' => JobMode::KEW_PA_10,
            'status' => JobStatus::NEW,
        ]);

        $this->expectException(InvalidArgumentException::class);
        
        // NEW -> COMPLETED (Skip inspection)
        $this->service->transitionStatus($job, JobStatus::COMPLETED);
    }

    /** @test */
    public function it_allows_valid_normal_transitions()
    {
        $job = WorkshopJob::factory()->create([
            'job_mode' => JobMode::NORMAL,
            'status' => JobStatus::NEW,
        ]);

        // NEW -> IN_PROGRESS
        $this->service->transitionStatus($job, JobStatus::IN_PROGRESS);
        $this->assertEquals(JobStatus::IN_PROGRESS, $job->fresh()->status);
    }

    /** @test */
    public function it_records_status_history()
    {
        $this->actingAs(\App\Models\User::factory()->create());

        $job = WorkshopJob::factory()->create([
            'job_mode' => JobMode::NORMAL,
            'status' => JobStatus::NEW,
        ]);

        $this->service->transitionStatus($job, JobStatus::IN_PROGRESS, 'Starting work');

        $this->assertDatabaseHas('job_status_histories', [
            'job_id' => $job->id,
            'from_status' => JobStatus::NEW->value,
            'to_status' => JobStatus::IN_PROGRESS->value,
            'notes' => 'Starting work'
        ]);
    }
}
