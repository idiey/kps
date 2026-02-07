<?php

namespace Tests\Unit\Services;

use App\Enums\JobMode;
use App\Enums\JobStatus;
use App\Models\User;
use App\Models\WorkshopJob;
use App\Services\KewPa10ApprovalService;
use App\Services\KewPa10ValidationService;
use App\Services\JobStatusService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KewPa10ApprovalServiceTest extends TestCase
{
    use RefreshDatabase;

    protected KewPa10ApprovalService $service;
    protected User $supervisor;
    protected User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->service = app(KewPa10ApprovalService::class);
        
        // Create supervisor with permissions
        $this->supervisor = User::factory()->create();
        $this->supervisor->assignRole('supervisor');
        
        // Create regular user
        $this->regularUser = User::factory()->create();
        $this->regularUser->assignRole('user');
    }

    /** @test */
    public function supervisor_can_approve_kew_job()
    {
        $job = WorkshopJob::factory()->kewPa10()->create([
            'status' => JobStatus::INSPECTION_IN_PROGRESS,
            'kew_inspector_name' => 'John Inspector',
            'kew_inspector_ic' => '123456789012',
            'kew_findings' => 'Vehicle needs brake replacement',
            'kew_recommendations' => 'Replace brake pads and rotors',
        ]);

        $this->actingAs($this->supervisor);

        $result = $this->service->approve($job, $this->supervisor);

        $this->assertEquals('approved', $result->kew_approval_status);
        $this->assertEquals($this->supervisor->id, $result->kew_approved_by_id);
        $this->assertNotNull($result->kew_approved_at);
        $this->assertNull($result->kew_rejection_reason);
        $this->assertEquals(JobStatus::INSPECTION_APPROVED, $result->status);
    }

    /** @test */
    public function supervisor_can_reject_kew_job()
    {
        $job = WorkshopJob::factory()->kewPa10()->create([
            'status' => JobStatus::INSPECTION_IN_PROGRESS,
            'kew_inspector_name' => 'John Inspector',
            'kew_inspector_ic' => '123456789012',
            'kew_findings' => 'Incomplete inspection',
            'kew_recommendations' => 'Need more details',
        ]);

        $this->actingAs($this->supervisor);

        $reason = 'Inspection findings are incomplete. Please provide more detailed analysis.';
        $result = $this->service->reject($job, $this->supervisor, $reason);

        $this->assertEquals('rejected', $result->kew_approval_status);
        $this->assertEquals($this->supervisor->id, $result->kew_approved_by_id);
        $this->assertNotNull($result->kew_approved_at);
        $this->assertEquals($reason, $result->kew_rejection_reason);
        $this->assertEquals(JobStatus::INSPECTION_REJECTED, $result->status);
    }

    /** @test */
    public function non_supervisor_cannot_approve_kew_job()
    {
        $job = WorkshopJob::factory()->kewPa10()->create([
            'status' => JobStatus::INSPECTION_IN_PROGRESS,
            'kew_inspector_name' => 'John Inspector',
            'kew_inspector_ic' => '123456789012',
            'kew_findings' => 'Test findings',
            'kew_recommendations' => 'Test recommendations',
        ]);

        $this->actingAs($this->regularUser);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('does not have supervisor permissions');

        $this->service->approve($job, $this->regularUser);
    }

    /** @test */
    public function cannot_approve_normal_job()
    {
        $job = WorkshopJob::factory()->create([
            'job_mode' => JobMode::NORMAL,
            'status' => JobStatus::IN_PROGRESS,
        ]);

        $this->actingAs($this->supervisor);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('is not a KEW.PA-10 job');

        $this->service->approve($job, $this->supervisor);
    }

    /** @test */
    public function cannot_approve_job_in_wrong_status()
    {
        $job = WorkshopJob::factory()->kewPa10()->create([
            'status' => JobStatus::NEW, // Wrong status
        ]);

        $this->actingAs($this->supervisor);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('cannot be approved in status');

        $this->service->approve($job, $this->supervisor);
    }

    /** @test */
    public function approval_requires_complete_inspection_details()
    {
        $job = WorkshopJob::factory()->kewPa10()->create([
            'status' => JobStatus::INSPECTION_IN_PROGRESS,
            'kew_inspector_name' => null, // Missing required field
            'kew_inspector_ic' => '123456789012',
            'kew_findings' => 'Test findings',
            'kew_recommendations' => 'Test recommendations',
        ]);

        $this->actingAs($this->supervisor);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Inspection details incomplete');

        $this->service->approve($job, $this->supervisor);
    }

    /** @test */
    public function rejection_requires_reason()
    {
        $job = WorkshopJob::factory()->kewPa10()->create([
            'status' => JobStatus::INSPECTION_IN_PROGRESS,
        ]);

        $this->actingAs($this->supervisor);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Rejection reason is required');

        $this->service->reject($job, $this->supervisor, '');
    }

    /** @test */
    public function can_approve_previously_rejected_job()
    {
        $job = WorkshopJob::factory()->kewPa10()->create([
            'status' => JobStatus::INSPECTION_REJECTED,
            'kew_approval_status' => 'rejected',
            'kew_rejection_reason' => 'Previous rejection reason',
            'kew_inspector_name' => 'John Inspector',
            'kew_inspector_ic' => '123456789012',
            'kew_findings' => 'Updated findings',
            'kew_recommendations' => 'Updated recommendations',
        ]);

        $this->actingAs($this->supervisor);

        $result = $this->service->approve($job, $this->supervisor);

        $this->assertEquals('approved', $result->kew_approval_status);
        $this->assertNull($result->kew_rejection_reason); // Cleared on approval
    }

    /** @test */
    public function it_retrieves_pending_approvals()
    {
        // Create mix of jobs
        WorkshopJob::factory()->kewPa10()->create([
            'status' => JobStatus::INSPECTION_IN_PROGRESS,
        ]);
        WorkshopJob::factory()->kewPa10()->create([
            'status' => JobStatus::INSPECTION_REJECTED,
        ]);
        WorkshopJob::factory()->kewPa10()->create([
            'status' => JobStatus::INSPECTION_APPROVED, // Already approved
        ]);
        WorkshopJob::factory()->create([
            'job_mode' => JobMode::NORMAL, // Not KEW
        ]);

        $pending = $this->service->getPendingApprovals();

        $this->assertCount(2, $pending); // Only 2 pending KEW jobs
    }

    /** @test */
    public function it_calculates_approval_statistics()
    {
        // Create approved jobs
        WorkshopJob::factory()->kewPa10()->count(3)->create([
            'kew_approval_status' => 'approved',
            'kew_approved_at' => now(),
        ]);

        // Create rejected jobs
        WorkshopJob::factory()->kewPa10()->count(2)->create([
            'kew_approval_status' => 'rejected',
            'kew_approved_at' => now(),
        ]);

        // Create pending jobs
        WorkshopJob::factory()->kewPa10()->create([
            'status' => JobStatus::INSPECTION_IN_PROGRESS,
        ]);

        $stats = $this->service->getApprovalStatistics();

        $this->assertEquals(3, $stats['total_approved']);
        $this->assertEquals(2, $stats['total_rejected']);
        $this->assertEquals(1, $stats['pending_approval']);
    }
}
