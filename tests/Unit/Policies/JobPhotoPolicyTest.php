<?php

namespace Tests\Unit\Policies;

use App\Enums\UserRole;
use App\Models\JobPhoto;
use App\Models\User;
use App\Models\WorkshopJob;
use App\Policies\JobPhotoPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobPhotoPolicyTest extends TestCase
{
    use RefreshDatabase;

    private JobPhotoPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new JobPhotoPolicy();
    }

    /** @test */
    public function test_all_users_can_view_any_job_photos()
    {
        $roles = UserRole::cases();

        foreach ($roles as $role) {
            $user = User::factory()->create(['role' => $role->value]);
            $this->assertTrue($this->policy->viewAny($user));
        }
    }

    /** @test */
    public function test_pentadbiran_can_view_all_photos()
    {
        $pentadbiran = User::factory()->create(['role' => UserRole::PENTADBIRAN->value]);
        $job = WorkshopJob::factory()->create();
        $photo = JobPhoto::factory()->create([
            'workshop_job_id' => $job->id,
            'user_id' => User::factory()->create()->id,
            'is_public' => false,
        ]);

        $this->assertTrue($this->policy->view($pentadbiran, $photo));
    }

    /** @test */
    public function test_all_users_can_view_public_photos()
    {
        $job = WorkshopJob::factory()->create();
        $photo = JobPhoto::factory()->create([
            'workshop_job_id' => $job->id,
            'user_id' => User::factory()->create()->id,
            'is_public' => true,
        ]);

        $roles = UserRole::cases();
        foreach ($roles as $role) {
            $user = User::factory()->create(['role' => $role->value]);
            $this->assertTrue($this->policy->view($user, $photo));
        }
    }

    /** @test */
    public function test_users_can_view_their_own_photos()
    {
        $user = User::factory()->create(['role' => UserRole::JURUTEKNIK->value]);
        $job = WorkshopJob::factory()->create();
        $photo = JobPhoto::factory()->create([
            'workshop_job_id' => $job->id,
            'user_id' => $user->id,
            'is_public' => false,
        ]);

        $this->assertTrue($this->policy->view($user, $photo));
    }

    /** @test */
    public function test_assigned_technician_can_view_private_job_photos()
    {
        $technician = User::factory()->create(['role' => UserRole::JURUTEKNIK->value]);
        $job = WorkshopJob::factory()->create(['assigned_to' => $technician->id]);
        $photo = JobPhoto::factory()->create([
            'workshop_job_id' => $job->id,
            'user_id' => User::factory()->create()->id,
            'is_public' => false,
        ]);

        $this->assertTrue($this->policy->view($technician, $photo));
    }

    /** @test */
    public function test_penyelia_can_view_all_photos()
    {
        $penyelia = User::factory()->create(['role' => UserRole::PENYELIA->value]);
        $job = WorkshopJob::factory()->create();
        $photo = JobPhoto::factory()->create([
            'workshop_job_id' => $job->id,
            'user_id' => User::factory()->create()->id,
            'is_public' => false,
        ]);

        $this->assertTrue($this->policy->view($penyelia, $photo));
    }

    /** @test */
    public function test_unrelated_user_cannot_view_private_photo()
    {
        $user = User::factory()->create(['role' => UserRole::JURUTEKNIK->value]);
        $job = WorkshopJob::factory()->create(['assigned_to' => User::factory()->create()->id]);
        $photo = JobPhoto::factory()->create([
            'workshop_job_id' => $job->id,
            'user_id' => User::factory()->create()->id,
            'is_public' => false,
        ]);

        $this->assertFalse($this->policy->view($user, $photo));
    }

    /** @test */
    public function test_pemeriksa_and_juruteknik_can_create_photos()
    {
        $pemeriksa = User::factory()->create(['role' => UserRole::PEMERIKSA->value]);
        $juruteknik = User::factory()->create(['role' => UserRole::JURUTEKNIK->value]);
        $pentadbiran = User::factory()->create(['role' => UserRole::PENTADBIRAN->value]);
        $penyelia = User::factory()->create(['role' => UserRole::PENYELIA->value]);

        $this->assertTrue($this->policy->create($pemeriksa));
        $this->assertTrue($this->policy->create($juruteknik));
        $this->assertFalse($this->policy->create($pentadbiran));
        $this->assertFalse($this->policy->create($penyelia));
    }

    /** @test */
    public function test_pentadbiran_can_update_any_photo()
    {
        $pentadbiran = User::factory()->create(['role' => UserRole::PENTADBIRAN->value]);
        $job = WorkshopJob::factory()->create();
        $photo = JobPhoto::factory()->create([
            'workshop_job_id' => $job->id,
            'user_id' => User::factory()->create()->id,
        ]);

        $this->assertTrue($this->policy->update($pentadbiran, $photo));
    }

    /** @test */
    public function test_users_can_update_their_own_photos()
    {
        $user = User::factory()->create(['role' => UserRole::PEMERIKSA->value]);
        $job = WorkshopJob::factory()->create();
        $photo = JobPhoto::factory()->create([
            'workshop_job_id' => $job->id,
            'user_id' => $user->id,
        ]);

        $this->assertTrue($this->policy->update($user, $photo));
    }

    /** @test */
    public function test_users_cannot_update_others_photos()
    {
        $user = User::factory()->create(['role' => UserRole::PEMERIKSA->value]);
        $job = WorkshopJob::factory()->create();
        $photo = JobPhoto::factory()->create([
            'workshop_job_id' => $job->id,
            'user_id' => User::factory()->create()->id,
        ]);

        $this->assertFalse($this->policy->update($user, $photo));
    }

    /** @test */
    public function test_pentadbiran_can_change_any_photo_visibility()
    {
        $pentadbiran = User::factory()->create(['role' => UserRole::PENTADBIRAN->value]);
        $job = WorkshopJob::factory()->create();
        $photo = JobPhoto::factory()->create([
            'workshop_job_id' => $job->id,
            'user_id' => User::factory()->create()->id,
        ]);

        $this->assertTrue($this->policy->changeVisibility($pentadbiran, $photo));
    }

    /** @test */
    public function test_photo_owners_can_change_visibility()
    {
        $owner = User::factory()->create(['role' => UserRole::JURUTEKNIK->value]);
        $job = WorkshopJob::factory()->create();
        $photo = JobPhoto::factory()->create([
            'workshop_job_id' => $job->id,
            'user_id' => $owner->id,
        ]);

        $this->assertTrue($this->policy->changeVisibility($owner, $photo));
    }

    /** @test */
    public function test_pentadbiran_can_delete_any_photo()
    {
        $pentadbiran = User::factory()->create(['role' => UserRole::PENTADBIRAN->value]);
        $job = WorkshopJob::factory()->create();
        $photo = JobPhoto::factory()->create([
            'workshop_job_id' => $job->id,
            'user_id' => User::factory()->create()->id,
        ]);

        $this->assertTrue($this->policy->delete($pentadbiran, $photo));
    }

    /** @test */
    public function test_users_can_delete_their_own_photos()
    {
        $user = User::factory()->create(['role' => UserRole::PEMERIKSA->value]);
        $job = WorkshopJob::factory()->create();
        $photo = JobPhoto::factory()->create([
            'workshop_job_id' => $job->id,
            'user_id' => $user->id,
        ]);

        $this->assertTrue($this->policy->delete($user, $photo));
    }

    /** @test */
    public function test_pentadbiran_can_restore_any_photo()
    {
        $pentadbiran = User::factory()->create(['role' => UserRole::PENTADBIRAN->value]);
        $job = WorkshopJob::factory()->create();
        $photo = JobPhoto::factory()->create([
            'workshop_job_id' => $job->id,
            'user_id' => User::factory()->create()->id,
        ]);

        $this->assertTrue($this->policy->restore($pentadbiran, $photo));
    }

    /** @test */
    public function test_users_can_restore_their_own_photos()
    {
        $user = User::factory()->create(['role' => UserRole::JURUTEKNIK->value]);
        $job = WorkshopJob::factory()->create();
        $photo = JobPhoto::factory()->create([
            'workshop_job_id' => $job->id,
            'user_id' => $user->id,
        ]);

        $this->assertTrue($this->policy->restore($user, $photo));
    }

    /** @test */
    public function test_only_pentadbiran_can_force_delete_photos()
    {
        $pentadbiran = User::factory()->create(['role' => UserRole::PENTADBIRAN->value]);
        $pemeriksa = User::factory()->create(['role' => UserRole::PEMERIKSA->value]);
        $job = WorkshopJob::factory()->create();
        $photo = JobPhoto::factory()->create([
            'workshop_job_id' => $job->id,
            'user_id' => $pemeriksa->id,
        ]);

        $this->assertTrue($this->policy->forceDelete($pentadbiran, $photo));
        $this->assertFalse($this->policy->forceDelete($pemeriksa, $photo));
    }
}
