<?php

use App\Enums\JobPriority;
use App\Enums\JobStatus;
use App\Models\Asset;
use App\Models\GovernmentDepartment;
use App\Models\KewPA10;
use App\Models\User;
use App\Models\WorkshopJob;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'pentadbiran']);
    $this->penyelia = User::factory()->create(['role' => 'penyelia']);
    $this->pemeriksa = User::factory()->create(['role' => 'pemeriksa']);
    $this->juruteknik = User::factory()->create(['role' => 'juruteknik']);

    $this->department = GovernmentDepartment::create([
        'name' => 'Ministry of Health',
        'department_code' => 'MOH',
        'ministry' => 'Health',
        'contact_person' => 'Dr. Ahmad',
        'email' => 'ahmad@moh.gov.my',
        'phone' => '+60123456789',
        'address' => 'Putrajaya',
        'city' => 'Putrajaya',
        'state' => 'Wilayah Persekutuan',
        'postcode' => '62590',
        'is_active' => true,
    ]);

    $this->asset = Asset::create([
        'government_department_id' => $this->department->id,
        'asset_tag' => 'MOH-VEH-001',
        'asset_type' => 'Vehicle',
        'asset_name' => 'Toyota Hilux',
        'description' => 'Official vehicle',
        'location' => 'Main Office',
        'current_condition' => 'operational',
    ]);
});

test('admin can view kew pa 10 index page', function () {
    KewPA10::factory()->count(5)->create();

    $response = $this->actingAs($this->admin)
        ->get('/kew-pa-10');

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->component('KewPA10/Index')
        ->has('kewPA10s.data', 5)
    );
});

test('kew pa 10 index can be filtered by department', function () {
    $otherDept = GovernmentDepartment::factory()->create();

    KewPA10::factory()->count(3)->create(['government_department_id' => $this->department->id]);
    KewPA10::factory()->count(2)->create(['government_department_id' => $otherDept->id]);

    $response = $this->actingAs($this->admin)
        ->get('/kew-pa-10?government_department_id=' . $this->department->id);

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->has('kewPA10s.data', 3)
    );
});

test('kew pa 10 index can be filtered by priority', function () {
    KewPA10::factory()->count(2)->create(['priority' => JobPriority::HIGH]);
    KewPA10::factory()->count(3)->create(['priority' => JobPriority::MEDIUM]);

    $response = $this->actingAs($this->admin)
        ->get('/kew-pa-10?priority=high');

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->has('kewPA10s.data', 2)
    );
});

test('kew pa 10 index can be filtered by verified status', function () {
    KewPA10::factory()->count(2)->create([
        'form_completeness_verified' => true,
        'signatures_verified' => true,
    ]);
    KewPA10::factory()->count(3)->create([
        'form_completeness_verified' => false,
        'signatures_verified' => false,
    ]);

    $response = $this->actingAs($this->admin)
        ->get('/kew-pa-10?verified=true');

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->has('kewPA10s.data', 2)
    );
});

test('admin can view kew pa 10 create page', function () {
    $response = $this->actingAs($this->admin)
        ->get('/kew-pa-10/create');

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->component('KewPA10/Create')
        ->has('governmentDepartments')
        ->has('assets')
        ->has('priorities')
    );
});

test('non admin cannot access kew pa 10 create page', function () {
    $response = $this->actingAs($this->penyelia)
        ->get('/kew-pa-10/create');

    $response->assertStatus(403);
});

test('admin can create new kew pa 10 form', function () {
    $data = [
        'kew_pa_10_number' => 'KEW.PA-10/MOH/2025/001',
        'government_department_id' => $this->department->id,
        'asset_id' => $this->asset->id,
        'description' => 'Engine repair needed',
        'priority' => 'high',
        'budget_allocation_reference' => 'BA-2025-001',
        'received_date' => now()->format('Y-m-d'),
    ];

    $response = $this->actingAs($this->admin)
        ->post('/kew-pa-10', $data);

    $response->assertRedirect();
    $this->assertDatabaseHas('kew_pa_10s', [
        'kew_pa_10_number' => 'KEW.PA-10/MOH/2025/001',
        'government_department_id' => $this->department->id,
        'asset_id' => $this->asset->id,
    ]);
});

test('kew pa 10 creation validates required fields', function () {
    $response = $this->actingAs($this->admin)
        ->post('/kew-pa-10', []);

    $response->assertSessionHasErrors([
        'kew_pa_10_number',
        'government_department_id',
        'asset_id',
        'description',
        'priority',
    ]);
});

test('kew pa 10 number must be unique', function () {
    KewPA10::factory()->create(['kew_pa_10_number' => 'KEW.PA-10/TEST/001']);

    $response = $this->actingAs($this->admin)
        ->post('/kew-pa-10', [
            'kew_pa_10_number' => 'KEW.PA-10/TEST/001',
            'government_department_id' => $this->department->id,
            'asset_id' => $this->asset->id,
            'description' => 'Test',
            'priority' => 'normal',
        ]);

    $response->assertSessionHasErrors(['kew_pa_10_number']);
});

test('users can view kew pa 10 detail page', function () {
    $kew = KewPA10::factory()->create();

    $response = $this->actingAs($this->penyelia)
        ->get("/kew-pa-10/{$kew->id}");

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->component('KewPA10/Show')
        ->has('kewPA10')
    );
});

test('admin can verify kew pa 10 form', function () {
    $kew = KewPA10::factory()->create([
        'form_completeness_verified' => false,
        'signatures_verified' => false,
    ]);

    $response = $this->actingAs($this->admin)
        ->post("/kew-pa-10/{$kew->id}/verify", [
            'form_completeness_verified' => true,
            'signatures_verified' => true,
            'verification_notes' => 'Form verified successfully',
        ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('kew_pa_10s', [
        'id' => $kew->id,
        'form_completeness_verified' => true,
        'signatures_verified' => true,
        'verification_notes' => 'Form verified successfully',
    ]);
});

test('non admin cannot verify kew pa 10 form', function () {
    $kew = KewPA10::factory()->create();

    $response = $this->actingAs($this->pemeriksa)
        ->post("/kew-pa-10/{$kew->id}/verify", [
            'form_completeness_verified' => true,
            'signatures_verified' => true,
        ]);

    $response->assertStatus(403);
});

test('admin can create workshop job from verified kew pa 10', function () {
    $kew = KewPA10::factory()->create([
        'form_completeness_verified' => true,
        'signatures_verified' => true,
    ]);

    $response = $this->actingAs($this->admin)
        ->post("/kew-pa-10/{$kew->id}/create-job");

    $response->assertRedirect();

    $kew->refresh();
    expect($kew->workshopJob)->not->toBeNull();
    expect($kew->workshopJob->status)->toBe(JobStatus::NEW);
})->skip('Controller method not yet implemented');

test('cannot create workshop job from unverified kew pa 10', function () {
    $kew = KewPA10::factory()->create([
        'form_completeness_verified' => false,
        'signatures_verified' => false,
    ]);

    $response = $this->actingAs($this->admin)
        ->post("/kew-pa-10/{$kew->id}/create-job");

    $response->assertSessionHasErrors();
})->skip('Controller method not yet implemented');

test('admin can update kew pa 10 form', function () {
    $kew = KewPA10::factory()->create();

    $response = $this->actingAs($this->admin)
        ->put("/kew-pa-10/{$kew->id}", [
            'kew_pa_10_number' => $kew->kew_pa_10_number,
            'government_department_id' => $kew->government_department_id,
            'asset_id' => $kew->asset_id,
            'description' => 'Updated description',
            'priority' => 'urgent',
            'budget_allocation_reference' => 'BA-UPDATED',
        ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('kew_pa_10s', [
        'id' => $kew->id,
        'description' => 'Updated description',
        'priority' => 'urgent',
    ]);
})->skip('Controller method not yet implemented');

test('admin can delete kew pa 10 form', function () {
    $kew = KewPA10::factory()->create();

    $response = $this->actingAs($this->admin)
        ->delete("/kew-pa-10/{$kew->id}");

    $response->assertRedirect();
    $this->assertSoftDeleted('kew_pa_10s', ['id' => $kew->id]);
});

test('non admin cannot delete kew pa 10 form', function () {
    $kew = KewPA10::factory()->create();

    $response = $this->actingAs($this->penyelia)
        ->delete("/kew-pa-10/{$kew->id}");

    $response->assertStatus(403);
});

test('cannot delete kew pa 10 with associated workshop job', function () {
    $kew = KewPA10::factory()->create();
    $job = WorkshopJob::factory()->create(['kew_pa_10_id' => $kew->id]);

    $response = $this->actingAs($this->admin)
        ->delete("/kew-pa-10/{$kew->id}");

    $response->assertSessionHasErrors();
    $this->assertDatabaseHas('kew_pa_10s', ['id' => $kew->id]);
})->skip('Controller validation not yet implemented');

test('admin can prepare asset for return to department', function () {
    $kew = KewPA10::factory()->create();
    $job = WorkshopJob::factory()->create([
        'kew_pa_10_id' => $kew->id,
        'status' => JobStatus::COMPLETED,
    ]);

    $response = $this->actingAs($this->admin)
        ->get("/jobs/{$job->id}/prepare-return");

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->component('KewPA10/PrepareReturn')
    );
})->skip('Controller method not yet implemented');

test('admin can mark asset as returned to department', function () {
    $kew = KewPA10::factory()->create();
    $job = WorkshopJob::factory()->create([
        'kew_pa_10_id' => $kew->id,
        'status' => JobStatus::COMPLETED,
    ]);

    $response = $this->actingAs($this->admin)
        ->post("/jobs/{$job->id}/mark-returned", [
            'return_notes' => 'Asset returned in good condition',
        ]);

    $response->assertRedirect();

    $job->refresh();
    expect($job->status)->toBe(JobStatus::KEW_PA_10_RETURNED);
})->skip('Controller method not yet implemented');
