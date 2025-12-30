<?php

namespace Tests\Unit\Policies;

use App\Enums\UserRole;
use App\Models\RepairCompletionReport;
use App\Models\User;
use App\Models\WorkshopJob;
use App\Policies\RepairCompletionReportPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RepairCompletionReportPolicyTest extends TestCase
{
    use RefreshDatabase;

    private RepairCompletionReportPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new RepairCompletionReportPolicy();
    }

    /** @test */
    public function test_all_users_can_view_any_repair_completion_reports()
    {
        $roles = UserRole::cases();

        foreach ($roles as $role) {
            $user = User::factory()->create(['role' => $role->value]);
            $this->assertTrue($this->policy->viewAny($user));
        }
    }

    /** @test */
    public function test_all_users_can_view_individual_repair_completion_report()
    {
        $job = WorkshopJob::factory()->create();
        $technician = User::factory()->create(['role' => UserRole::JURUTEKNIK->value]);
        $report = RepairCompletionReport::factory()->create([
            'workshop_job_id' => $job->id,
            'technician_id' => $technician->id,
        ]);

        $roles = UserRole::cases();
        foreach ($roles as $role) {
            $user = User::factory()->create(['role' => $role->value]);
            $this->assertTrue($this->policy->view($user, $report));
        }
    }

    /** @test */
    public function test_only_juruteknik_can_create_repair_completion_reports()
    {
        $juruteknik = User::factory()->create(['role' => UserRole::JURUTEKNIK->value]);
        $pentadbiran = User::factory()->create(['role' => UserRole::PENTADBIRAN->value]);
        $pemeriksa = User::factory()->create(['role' => UserRole::PEMERIKSA->value]);
        $penyelia = User::factory()->create(['role' => UserRole::PENYELIA->value]);

        $this->assertTrue($this->policy->create($juruteknik));
        $this->assertFalse($this->policy->create($pentadbiran));
        $this->assertFalse($this->policy->create($pemeriksa));
        $this->assertFalse($this->policy->create($penyelia));
    }

    /** @test */
    public function test_technician_can_update_own_unsigned_report()
    {
        $technician = User::factory()->create(['role' => UserRole::JURUTEKNIK->value]);
        $job = WorkshopJob::factory()->create();
        $report = RepairCompletionReport::factory()->create([
            'workshop_job_id' => $job->id,
            'technician_id' => $technician->id,
            'technician_signature' => null,
        ]);

        $this->assertTrue($this->policy->update($technician, $report));
    }

    /** @test */
    public function test_technician_cannot_update_signed_report()
    {
        $technician = User::factory()->create(['role' => UserRole::JURUTEKNIK->value]);
        $job = WorkshopJob::factory()->create();
        $report = RepairCompletionReport::factory()->create([
            'workshop_job_id' => $job->id,
            'technician_id' => $technician->id,
            'technician_signature' => 'signature_data',
        ]);

        $this->assertFalse($this->policy->update($technician, $report));
    }

    /** @test */
    public function test_technician_cannot_update_others_report()
    {
        $technician1 = User::factory()->create(['role' => UserRole::JURUTEKNIK->value]);
        $technician2 = User::factory()->create(['role' => UserRole::JURUTEKNIK->value]);
        $job = WorkshopJob::factory()->create();
        $report = RepairCompletionReport::factory()->create([
            'workshop_job_id' => $job->id,
            'technician_id' => $technician1->id,
            'technician_signature' => null,
        ]);

        $this->assertFalse($this->policy->update($technician2, $report));
    }

    /** @test */
    public function test_technician_can_add_parts_to_own_unsigned_report()
    {
        $technician = User::factory()->create(['role' => UserRole::JURUTEKNIK->value]);
        $job = WorkshopJob::factory()->create();
        $report = RepairCompletionReport::factory()->create([
            'workshop_job_id' => $job->id,
            'technician_id' => $technician->id,
            'technician_signature' => null,
        ]);

        $this->assertTrue($this->policy->addParts($technician, $report));
    }

    /** @test */
    public function test_technician_cannot_add_parts_to_signed_report()
    {
        $technician = User::factory()->create(['role' => UserRole::JURUTEKNIK->value]);
        $job = WorkshopJob::factory()->create();
        $report = RepairCompletionReport::factory()->create([
            'workshop_job_id' => $job->id,
            'technician_id' => $technician->id,
            'technician_signature' => 'signature_data',
        ]);

        $this->assertFalse($this->policy->addParts($technician, $report));
    }

    /** @test */
    public function test_technician_can_sign_own_unsigned_report()
    {
        $technician = User::factory()->create(['role' => UserRole::JURUTEKNIK->value]);
        $job = WorkshopJob::factory()->create();
        $report = RepairCompletionReport::factory()->create([
            'workshop_job_id' => $job->id,
            'technician_id' => $technician->id,
            'technician_signature' => null,
        ]);

        $this->assertTrue($this->policy->sign($technician, $report));
    }

    /** @test */
    public function test_technician_cannot_sign_already_signed_report()
    {
        $technician = User::factory()->create(['role' => UserRole::JURUTEKNIK->value]);
        $job = WorkshopJob::factory()->create();
        $report = RepairCompletionReport::factory()->create([
            'workshop_job_id' => $job->id,
            'technician_id' => $technician->id,
            'technician_signature' => 'signature_data',
        ]);

        $this->assertFalse($this->policy->sign($technician, $report));
    }

    /** @test */
    public function test_only_penyelia_can_review_reports()
    {
        $penyelia = User::factory()->create(['role' => UserRole::PENYELIA->value]);
        $pentadbiran = User::factory()->create(['role' => UserRole::PENTADBIRAN->value]);
        $pemeriksa = User::factory()->create(['role' => UserRole::PEMERIKSA->value]);
        $job = WorkshopJob::factory()->create();
        $report = RepairCompletionReport::factory()->create([
            'workshop_job_id' => $job->id,
            'technician_id' => User::factory()->create(['role' => UserRole::JURUTEKNIK->value])->id,
        ]);

        $this->assertTrue($this->policy->review($penyelia, $report));
        $this->assertFalse($this->policy->review($pentadbiran, $report));
        $this->assertFalse($this->policy->review($pemeriksa, $report));
    }

    /** @test */
    public function test_technician_can_delete_own_unsigned_report()
    {
        $technician = User::factory()->create(['role' => UserRole::JURUTEKNIK->value]);
        $job = WorkshopJob::factory()->create();
        $report = RepairCompletionReport::factory()->create([
            'workshop_job_id' => $job->id,
            'technician_id' => $technician->id,
            'technician_signature' => null,
        ]);

        $this->assertTrue($this->policy->delete($technician, $report));
    }

    /** @test */
    public function test_technician_cannot_delete_signed_report()
    {
        $technician = User::factory()->create(['role' => UserRole::JURUTEKNIK->value]);
        $job = WorkshopJob::factory()->create();
        $report = RepairCompletionReport::factory()->create([
            'workshop_job_id' => $job->id,
            'technician_id' => $technician->id,
            'technician_signature' => 'signature_data',
        ]);

        $this->assertFalse($this->policy->delete($technician, $report));
    }

    /** @test */
    public function test_pentadbiran_can_delete_any_report()
    {
        $pentadbiran = User::factory()->create(['role' => UserRole::PENTADBIRAN->value]);
        $technician = User::factory()->create(['role' => UserRole::JURUTEKNIK->value]);
        $job = WorkshopJob::factory()->create();
        $report = RepairCompletionReport::factory()->create([
            'workshop_job_id' => $job->id,
            'technician_id' => $technician->id,
            'technician_signature' => 'signature_data',
        ]);

        $this->assertTrue($this->policy->delete($pentadbiran, $report));
    }

    /** @test */
    public function test_only_pentadbiran_can_restore_reports()
    {
        $pentadbiran = User::factory()->create(['role' => UserRole::PENTADBIRAN->value]);
        $penyelia = User::factory()->create(['role' => UserRole::PENYELIA->value]);
        $job = WorkshopJob::factory()->create();
        $report = RepairCompletionReport::factory()->create([
            'workshop_job_id' => $job->id,
            'technician_id' => User::factory()->create(['role' => UserRole::JURUTEKNIK->value])->id,
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
        $report = RepairCompletionReport::factory()->create([
            'workshop_job_id' => $job->id,
            'technician_id' => User::factory()->create(['role' => UserRole::JURUTEKNIK->value])->id,
        ]);

        $this->assertTrue($this->policy->forceDelete($pentadbiran, $report));
        $this->assertFalse($this->policy->forceDelete($juruteknik, $report));
    }
}
