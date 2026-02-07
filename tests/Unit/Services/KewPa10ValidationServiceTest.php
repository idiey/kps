<?php

namespace Tests\Unit\Services;

use App\Enums\JobMode;
use App\Models\WorkshopJob;
use App\Services\KewPa10ValidationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class KewPa10ValidationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected KewPa10ValidationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new KewPa10ValidationService();
    }

    /** @test */
    public function it_validates_ready_for_approval()
    {
        $job = WorkshopJob::factory()->create([
            'job_mode' => JobMode::KEW_PA_10,
            // Missing required fields
            'kew_vehicle_registration' => null, 
        ]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing required KEW.PA-10 information');

        $this->service->ensureReadyForApproval($job);
    }

    /** @test */
    public function it_passes_ready_for_approval_when_complete()
    {
        $job = WorkshopJob::factory()->create([
            'job_mode' => JobMode::KEW_PA_10,
            'kew_vehicle_registration' => 'ABC1234',
            'kew_asset_tag' => 'K10/2023/001',
            'kew_department_name' => 'IT Dept',
            'kew_inspection_date' => now(),
        ]);

        // Should not throw exception
        $this->service->ensureReadyForApproval($job);
        $this->assertTrue(true);
    }

    /** @test */
    public function it_validates_inspection_completion()
    {
        $job = WorkshopJob::factory()->create([
            'job_mode' => JobMode::KEW_PA_10,
            // Missing findings
            'kew_findings' => null,
        ]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Inspection details incomplete');

        $this->service->ensureInspectionComplete($job);
    }
}
