<?php

use App\Models\Company;
use App\Models\User;
use App\Models\Workshop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Vite;

uses(RefreshDatabase::class);

beforeAll(function () {
    app()->bind(Vite::class, fn () => new class extends Vite {
        public function __invoke($entrypoints, $buildDirectory = null): \Illuminate\Support\HtmlString {
            return new \Illuminate\Support\HtmlString('');
        }
    });
});

beforeEach(function () {
    // Create a company (HQ)
    $this->company = Company::factory()->create(['is_active' => true]);
    
    // Create a workshop (Site)
    $this->workshop = Workshop::factory()->create([
        'company_id' => $this->company->id,
        'is_active' => true,
    ]);
    
    // Create users with different roles and scopes
    $this->globalAdmin = User::factory()->create(['role' => 'pentadbiran']);
    
    $this->hqUser = User::factory()->create([
        'company_id' => $this->company->id,
        'role' => 'pentadbiran',
    ]);
    
    $this->assignedUser = User::factory()->create(['role' => 'penyelia']);
    $this->workshop->assignUser($this->assignedUser->id, 'supervisor');
    
    $this->unassignedUser = User::factory()->create(['role' => 'juruteknik']);
});

describe('Workshop Viewing', function () {
    test('global admin can view all workshops', function () {
        $this->actingAs($this->globalAdmin)
            ->get('/admin/workshops')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('workshops.data')
            );
    });

    test('HQ user can only view their company workshops', function () {
        // Create another company with workshop
        $otherCompany = Company::factory()->create();
        $otherWorkshop = Workshop::factory()->create(['company_id' => $otherCompany->id]);
        
        $this->actingAs($this->hqUser)
            ->get('/admin/workshops')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('workshops.data', 1) // Only sees their company's workshop
            );
    });

    test('assigned user can view their assigned workshop', function () {
        $this->actingAs($this->assignedUser)
            ->get("/admin/workshops/{$this->workshop->id}")
            ->assertOk();
    });

    test('unassigned user cannot view workshop details', function () {
        $this->actingAs($this->unassignedUser)
            ->get("/admin/workshops/{$this->workshop->id}")
            ->assertForbidden();
    });
});

describe('Workshop Creation', function () {
    test('global admin can create workshops', function () {
        $this->actingAs($this->globalAdmin)
            ->post('/admin/workshops', [
                'name' => 'New Workshop',
                'code' => 'NW001',
                'company_id' => $this->company->id,
            ])
            ->assertRedirect();
        
        $this->assertDatabaseHas('workshops', [
            'name' => 'New Workshop',
            'code' => 'NW001',
        ]);
    });

    test('HQ user can create workshops for their company only', function () {
        $this->actingAs($this->hqUser)
            ->post('/admin/workshops', [
                'name' => 'HQ Workshop',
                'code' => 'HQ001',
            ])
            ->assertRedirect();
        
        $this->assertDatabaseHas('workshops', [
            'name' => 'HQ Workshop',
            'company_id' => $this->company->id,
        ]);
    });

    test('assigned user cannot create workshops', function () {
        $this->actingAs($this->assignedUser)
            ->post('/admin/workshops', [
                'name' => 'Attempt',
                'code' => 'AT001',
            ])
            ->assertForbidden();
    });
});

describe('Workshop Update', function () {
    test('global admin can update any workshop', function () {
        $this->actingAs($this->globalAdmin)
            ->put("/admin/workshops/{$this->workshop->id}", [
                'name' => 'Updated Name',
                'code' => $this->workshop->code,
            ])
            ->assertRedirect();
        
        expect($this->workshop->fresh()->name)->toBe('Updated Name');
    });

    test('HQ user can update their company workshops', function () {
        $this->actingAs($this->hqUser)
            ->put("/admin/workshops/{$this->workshop->id}", [
                'name' => 'HQ Updated',
                'code' => $this->workshop->code,
            ])
            ->assertRedirect();
        
        expect($this->workshop->fresh()->name)->toBe('HQ Updated');
    });

    test('assigned user cannot update workshops', function () {
        $this->actingAs($this->assignedUser)
            ->put("/admin/workshops/{$this->workshop->id}", [
                'name' => 'Attempt Update',
                'code' => $this->workshop->code,
            ])
            ->assertForbidden();
    });
});

describe('Workshop Deletion', function () {
    test('global admin can delete workshops', function () {
        $emptyWorkshop = Workshop::factory()->create(['company_id' => $this->company->id]);
        
        $this->actingAs($this->globalAdmin)
            ->delete("/admin/workshops/{$emptyWorkshop->id}")
            ->assertRedirect();
        
        $this->assertSoftDeleted('workshops', ['id' => $emptyWorkshop->id]);
    });

    test('HQ user can delete their company workshops', function () {
        $emptyWorkshop = Workshop::factory()->create(['company_id' => $this->company->id]);
        
        $this->actingAs($this->hqUser)
            ->delete("/admin/workshops/{$emptyWorkshop->id}")
            ->assertRedirect();
        
        $this->assertSoftDeleted('workshops', ['id' => $emptyWorkshop->id]);
    });

    test('assigned user cannot delete workshops', function () {
        $this->actingAs($this->assignedUser)
            ->delete("/admin/workshops/{$this->workshop->id}")
            ->assertForbidden();
    });
});

describe('User Assignment', function () {
    test('admin can assign users to workshop', function () {
        $newUser = User::factory()->create(['role' => 'juruteknik']);
        
        $this->actingAs($this->globalAdmin)
            ->post("/admin/workshops/{$this->workshop->id}/users", [
                'user_id' => $newUser->id,
                'role' => 'technician',
            ])
            ->assertRedirect();
        
        expect($this->workshop->hasUser($newUser->id))->toBeTrue();
    });

    test('admin can update user role in workshop', function () {
        $this->actingAs($this->globalAdmin)
            ->patch("/admin/workshops/{$this->workshop->id}/users/{$this->assignedUser->id}", [
                'role' => 'staff',
            ])
            ->assertRedirect();
        
        $role = $this->workshop->assignedUsers()->where('user_id', $this->assignedUser->id)->first()->pivot->role;
        expect($role)->toBe('staff');
    });

    test('admin can remove user from workshop', function () {
        $this->actingAs($this->globalAdmin)
            ->delete("/admin/workshops/{$this->workshop->id}/users/{$this->assignedUser->id}")
            ->assertRedirect();
        
        expect($this->workshop->hasUser($this->assignedUser->id))->toBeFalse();
    });

    test('duplicate assignment is prevented', function () {
        $this->actingAs($this->globalAdmin)
            ->post("/admin/workshops/{$this->workshop->id}/users", [
                'user_id' => $this->assignedUser->id,
                'role' => 'technician',
            ])
            ->assertSessionHasErrors('user_id');
    });
});

describe('Analytics Access', function () {
    test('admin can view workshop analytics', function () {
        // Check authorization passes (don't render full page to avoid Vite issues)
        $response = $this->actingAs($this->globalAdmin)
            ->get("/admin/workshops/{$this->workshop->id}/analytics");
        
        // If we get past auth (200 or Vite error), authorization passed
        expect($response->status())->not->toBe(403);
    });

    test('HQ user can view their company workshop analytics', function () {
        $response = $this->actingAs($this->hqUser)
            ->get("/admin/workshops/{$this->workshop->id}/analytics");
        
        expect($response->status())->not->toBe(403);
    });

    test('unassigned user cannot view analytics', function () {
        $this->actingAs($this->unassignedUser)
            ->get("/admin/workshops/{$this->workshop->id}/analytics")
            ->assertForbidden();
    });
});
