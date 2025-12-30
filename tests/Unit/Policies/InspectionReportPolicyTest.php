<?php

namespace Tests\Unit\Policies;

use App\Enums\UserRole;
use App\Models\InspectionReport;
use App\Models\User;
use App\Models\WorkshopJob;
use App\Policies\InspectionReportPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InspectionReportPolicyTest extends TestCase
{
    use RefreshDatabase;

    private InspectionReportPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new InspectionReportPolicy();
    }

    /** @test */
    public function test_all_users_can_view_any_inspection_reports()
    {
        $roles = UserRole::cases();

        foreach ($roles as $role) {
            $user = User::factory()->create(['role' => $role->value]);
            $this->assertTrue($this->policy->viewAny($user));
        }
    }

    /** @test */
    public function test_all_users_can_view_individual_inspection_report()
    {
        $job = WorkshopJob::factory()->create();
        $inspector = User::factory()->create(['role' => UserRole::PEMERIKSA->value]);
        $report = InspectionReport::factory()->create([
            'workshop_job_id' => $job->id,
            'inspector_id' => $inspector->id,
        ]);

        $roles = UserRole::cases();
        foreach ($roles as $role) {
            $user = User::factory()->create(['role' => $role->value]);
            $this->assertTrue($this->policy->view($user, $report));
        }
    }

    /** @test */
    public function test_only_pemeriksa_can_create_inspection_reports()
    {
        $pemeriksa = User::factory()->create(['role' => UserRole::PEMERIKSA->value]);
        $pentadbiran = User::factory()->create(['role' => UserRole::PENTADBIRAN->value]);
        $penyelia = User::factory()->create(['role' => UserRole::PENYELIA->value]);
        $juruteknik = User::factory()->create(['role' => UserRole::JURUTEKNIK->value]);

        $this->assertTrue($this->policy->create($pemeriksa));
        $this->assertFalse($this->policy->create($pentadbiran));
        $this->assertFalse($this->policy->create($penyelia));
        $this->assertFalse($this->policy->create($juruteknik));
    }

    /** @test */
    public function test_inspector_can_update_own_pending_report()
    {
        $inspector = User::factory()->create(['role' => UserRole::PEMERIKSA->value]);
        $job = WorkshopJob::factory()->create();
        $report = InspectionReport::factory()->create([
            'workshop_job_id' => $job->id,
            'inspector_id' => $inspector->id,
            'approval_status' => 'pending',
        ]);

        $this->assertTrue($this->policy->update($inspector, $report));
    }

    /** @test */
    public function test_inspector_cannot_update_others_report()
    {
        $inspector1 = User::factory()->create(['role' => UserRole::PEMERIKSA->value]);
        $inspector2 = User::factory()->create(['role' => UserRole::PEMERIKSA->value]);
        $job = WorkshopJob::factory()->create();
        $report = InspectionReport::factory()->create([
            'workshop_job_id' => $job->id,
            'inspector_id' => $inspector1->id,
            'approval_status' => 'pending',
        ]);

        $this->assertFalse($this->policy->update($inspector2, $report));
    }

    /** @test */
    public function test_inspector_cannot_update_approved_report()
    {
        $inspector = User::factory()->create(['role' => UserRole::PEMERIKSA->value]);
        $job = WorkshopJob::factory()->create();
        $report = InspectionReport::factory()->create([
            'workshop_job_id' => $job->id,
            'inspector_id' => $inspector->id,
            'approval_status' => 'approved',
        ]);

        $this->assertFalse($this->policy->update($inspector, $report));
    }

    /** @test */
    public function test_only_penyelia_can_approve_pending_reports()
    {
        $penyelia = User::factory()->create(['role' => UserRole::PENYELIA->value]);
        $pemeriksa = User::factory()->create(['role' => UserRole::PEMERIKSA->value]);
        $job = WorkshopJob::factory()->create();
        $report = InspectionReport::factory()->create([
            'workshop_job_id' => $job->id,
            'inspector_id' => $pemeriksa->id,
            'approval_status' => 'pending',
        ]);

        $this->assertTrue($this->policy->approve($penyelia, $report));
        $this->assertFalse($this->policy->approve($pemeriksa, $report));
    }

    /** @test */
    public function test_penyelia_cannot_approve_non_pending_reports()
    {
        $penyelia = User::factory()->create(['role' => UserRole::PENYELIA->value]);
        $pemeriksa = User::factory()->create(['role' => UserRole::PEMERIKSA->value]);
        $job = WorkshopJob::factory()->create();
        $approvedReport = InspectionReport::factory()->create([
            'workshop_job_id' => $job->id,
            'inspector_id' => $pemeriksa->id,
            'approval_status' => 'approved',
        ]);

        $this->assertFalse($this->policy->approve($penyelia, $approvedReport));
    }

    /** @test */
    public function test_only_penyelia_can_reject_pending_reports()
    {
        $penyelia = User::factory()->create(['role' => UserRole::PENYELIA->value]);
        $pentadbiran = User::factory()->create(['role' => UserRole::PENTADBIRAN->value]);
        $job = WorkshopJob::factory()->create();
        $report = InspectionReport::factory()->create([
            'workshop_job_id' => $job->id,
            'inspector_id' => User::factory()->create(['role' => UserRole::PEMERIKSA->value])->id,
            'approval_status' => 'pending',
        ]);

        $this->assertTrue($this->policy->reject($penyelia, $report));
        $this->assertFalse($this->policy->reject($pentadbiran, $report));
    }

    /** @test */
    public function test_inspector_can_add_photos_to_own_pending_report()
    {
        $inspector = User::factory()->create(['role' => UserRole::PEMERIKSA->value]);
        $job = WorkshopJob::factory()->create();
        $report = InspectionReport::factory()->create([
            'workshop_job_id' => $job->id,
            'inspector_id' => $inspector->id,
            'approval_status' => 'pending',
        ]);

        $this->assertTrue($this->policy->addPhotos($inspector, $report));
    }

    /** @test */
    public function test_inspector_cannot_add_photos_to_approved_report()
    {
        $inspector = User::factory()->create(['role' => UserRole::PEMERIKSA->value]);
        $job = WorkshopJob::factory()->create();
        $report = InspectionReport::factory()->create([
            'workshop_job_id' => $job->id,
            'inspector_id' => $inspector->id,
            'approval_status' => 'approved',
        ]);

        $this->assertFalse($this->policy->addPhotos($inspector, $report));
    }

    /** @test */
    public function test_inspector_can_delete_own_pending_report()
    {
        $inspector = User::factory()->create(['role' => UserRole::PEMERIKSA->value]);
        $job = WorkshopJob::factory()->create();
        $report = InspectionReport::factory()->create([
            'workshop_job_id' => $job->id,
            'inspector_id' => $inspector->id,
            'approval_status' => 'pending',
        ]);

        $this->assertTrue($this->policy->delete($inspector, $report));
    }

    /** @test */
    public function test_pentadbiran_can_delete_any_report()
    {
        $pentadbiran = User::factory()->create(['role' => UserRole::PENTADBIRAN->value]);
        $inspector = User::factory()->create(['role' => UserRole::PEMERIKSA->value]);
        $job = WorkshopJob::factory()->create();
        $report = InspectionReport::factory()->create([
            'workshop_job_id' => $job->id,
            'inspector_id' => $inspector->id,
            'approval_status' => 'approved',
        ]);

        $this->assertTrue($this->policy->delete($pentadbiran, $report));
    }

    /** @test */
    public function test_only_pentadbiran_can_restore_reports()
    {
        $pentadbiran = User::factory()->create(['role' => UserRole::PENTADBIRAN->value]);
        $penyelia = User::factory()->create(['role' => UserRole::PENYELIA->value]);
        $job = WorkshopJob::factory()->create();
        $report = InspectionReport::factory()->create([
            'workshop_job_id' => $job->id,
            'inspector_id' => User::factory()->create(['role' => UserRole::PEMERIKSA->value])->id,
        ]);

        $this->assertTrue($this->policy->restore($pentadbiran, $report));
        $this->assertFalse($this->policy->restore($penyelia, $report));
    }

    /** @test */
    public function test_only_pentadbiran_can_force_delete_reports()
    {
        $pentadbiran = User::factory()->create(['role' => UserRole::PENTADBIRAN->value]);
        $juruteknik = User::factory()->create(['role' => UserRole::JURUTEKNIK->value]);
        $job = WorkshopJob::factory()->create();
        $report = InspectionReport::factory()->create([
            'workshop_job_id' => $job->id,
            'inspector_id' => User::factory()->create(['role' => UserRole::PEMERIKSA->value])->id,
        ]);

        $this->assertTrue($this->policy->forceDelete($pentadbiran, $report));
        $this->assertFalse($this->policy->forceDelete($juruteknik, $report));
    }
}
