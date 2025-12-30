<?php

namespace Tests\Unit\Policies;

use App\Enums\UserRole;
use App\Models\KewPA10;
use App\Models\User;
use App\Policies\KewPA10Policy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KewPA10PolicyTest extends TestCase
{
    use RefreshDatabase;

    private KewPA10Policy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new KewPA10Policy();
    }

    /** @test */
    public function test_all_users_can_view_any_kew_pa_10_forms()
    {
        $roles = UserRole::cases();

        foreach ($roles as $role) {
            $user = User::factory()->create(['role' => $role->value]);
            $this->assertTrue($this->policy->viewAny($user));
        }
    }

    /** @test */
    public function test_all_users_can_view_individual_kew_pa_10_form()
    {
        $kewPA10 = KewPA10::factory()->create();
        $roles = UserRole::cases();

        foreach ($roles as $role) {
            $user = User::factory()->create(['role' => $role->value]);
            $this->assertTrue($this->policy->view($user, $kewPA10));
        }
    }

    /** @test */
    public function test_only_pentadbiran_can_create_kew_pa_10_forms()
    {
        $pentadbiran = User::factory()->create(['role' => UserRole::PENTADBIRAN->value]);
        $pemeriksa = User::factory()->create(['role' => UserRole::PEMERIKSA->value]);
        $penyelia = User::factory()->create(['role' => UserRole::PENYELIA->value]);
        $juruteknik = User::factory()->create(['role' => UserRole::JURUTEKNIK->value]);

        $this->assertTrue($this->policy->create($pentadbiran));
        $this->assertFalse($this->policy->create($pemeriksa));
        $this->assertFalse($this->policy->create($penyelia));
        $this->assertFalse($this->policy->create($juruteknik));
    }

    /** @test */
    public function test_only_pentadbiran_can_update_kew_pa_10_forms()
    {
        $kewPA10 = KewPA10::factory()->create();
        $pentadbiran = User::factory()->create(['role' => UserRole::PENTADBIRAN->value]);
        $pemeriksa = User::factory()->create(['role' => UserRole::PEMERIKSA->value]);

        $this->assertTrue($this->policy->update($pentadbiran, $kewPA10));
        $this->assertFalse($this->policy->update($pemeriksa, $kewPA10));
    }

    /** @test */
    public function test_only_pentadbiran_can_verify_kew_pa_10_forms()
    {
        $kewPA10 = KewPA10::factory()->create();
        $pentadbiran = User::factory()->create(['role' => UserRole::PENTADBIRAN->value]);
        $penyelia = User::factory()->create(['role' => UserRole::PENYELIA->value]);

        $this->assertTrue($this->policy->verify($pentadbiran, $kewPA10));
        $this->assertFalse($this->policy->verify($penyelia, $kewPA10));
    }

    /** @test */
    public function test_only_pentadbiran_can_return_kew_pa_10_to_department()
    {
        $kewPA10 = KewPA10::factory()->create();
        $pentadbiran = User::factory()->create(['role' => UserRole::PENTADBIRAN->value]);
        $penyelia = User::factory()->create(['role' => UserRole::PENYELIA->value]);

        $this->assertTrue($this->policy->returnToDepartment($pentadbiran, $kewPA10));
        $this->assertFalse($this->policy->returnToDepartment($penyelia, $kewPA10));
    }

    /** @test */
    public function test_only_pentadbiran_can_delete_kew_pa_10_forms()
    {
        $kewPA10 = KewPA10::factory()->create();
        $pentadbiran = User::factory()->create(['role' => UserRole::PENTADBIRAN->value]);
        $juruteknik = User::factory()->create(['role' => UserRole::JURUTEKNIK->value]);

        $this->assertTrue($this->policy->delete($pentadbiran, $kewPA10));
        $this->assertFalse($this->policy->delete($juruteknik, $kewPA10));
    }

    /** @test */
    public function test_only_pentadbiran_can_restore_kew_pa_10_forms()
    {
        $kewPA10 = KewPA10::factory()->create();
        $pentadbiran = User::factory()->create(['role' => UserRole::PENTADBIRAN->value]);
        $pemeriksa = User::factory()->create(['role' => UserRole::PEMERIKSA->value]);

        $this->assertTrue($this->policy->restore($pentadbiran, $kewPA10));
        $this->assertFalse($this->policy->restore($pemeriksa, $kewPA10));
    }

    /** @test */
    public function test_only_pentadbiran_can_force_delete_kew_pa_10_forms()
    {
        $kewPA10 = KewPA10::factory()->create();
        $pentadbiran = User::factory()->create(['role' => UserRole::PENTADBIRAN->value]);
        $penyelia = User::factory()->create(['role' => UserRole::PENYELIA->value]);

        $this->assertTrue($this->policy->forceDelete($pentadbiran, $kewPA10));
        $this->assertFalse($this->policy->forceDelete($penyelia, $kewPA10));
    }
}
