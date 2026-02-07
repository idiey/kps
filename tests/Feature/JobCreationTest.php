<?php

use App\Models\User;
use App\Models\Customer;
use App\Models\WorkshopJob;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->frontdesk = User::factory()->create(['role' => 'kaunter']); // 'kaunter' is frontdesk based on role names
    $this->technician = User::factory()->create(['role' => 'juruteknik']);
    
    $this->frontdeskRole = ensureRole('kaunter');
    $this->technicianRole = ensureRole('juruteknik');
    
    $this->frontdesk->syncRoles([$this->frontdeskRole]);
    $this->technician->syncRoles([$this->technicianRole]);

    // Create a default workflow if the DB constraint exists (defensive)
    // We can use the helper from Pest.php if needed, but let's try relying on factory/null first.
    // However, since WorkshopJobTest uses it, let's create one to be safe and avoid SQL errors if the column is not nullable.
    // Actually, JobService doesn't assign it. So if the column is not nullable, JobService would fail.
    // If JobService succeeds, then the column is nullable or has default.

    $this->inspector = User::factory()->create(['role' => 'pemeriksa']);
    $this->inspectorRole = ensureRole('pemeriksa');
    $this->inspector->syncRoles([$this->inspectorRole]);
});

test('frontdesk can access job creation pages', function () {
    $this->actingAs($this->frontdesk)
        ->get(route('jobs.select-mode'))
        ->assertOk();

    $this->actingAs($this->frontdesk)
        ->get(route('jobs.create.normal'))
        ->assertOk();
        
    $this->actingAs($this->frontdesk)
        ->get(route('jobs.create.kew'))
        ->assertOk();
});

test('technician cannot access job creation pages', function () {
    $this->actingAs($this->technician)
        ->get(route('jobs.select-mode'))
        ->assertForbidden();

    $this->actingAs($this->technician)
        ->get(route('jobs.create.normal'))
        ->assertForbidden();
});

test('inspector cannot access job creation pages', function () {
    $this->actingAs($this->inspector)
        ->get(route('jobs.select-mode'))
        ->assertForbidden();

    $this->actingAs($this->inspector)
        ->get(route('jobs.create.kew'))
        ->assertForbidden();
    
    $this->actingAs($this->inspector)
        ->get(route('jobs.create.normal'))
        ->assertForbidden();
});

test('can create normal job', function () {
    $customer = Customer::factory()->create();
    
    // We might need to permit the role 'kaunter' to access the route.
    // The previous implementation used 'create' policy which checks roles.
    
    $response = $this->actingAs($this->frontdesk)
        ->post(route('jobs.store'), [
            'job_mode' => 'NORMAL',
            'customer_id' => $customer->id,
            'title' => 'Test Job',
            'description' => 'Description',
            'priority' => 'medium',
        ]);
        
    $response->assertRedirect();
    $this->assertDatabaseHas('workshop_jobs', [
        'title' => 'Test Job',
        'job_mode' => 'NORMAL',
    ]);
});

test('validates job_mode presence', function () {
    $response = $this->actingAs($this->frontdesk)
        ->post(route('jobs.store'), [
            'title' => 'Test Job',
            // missing job_mode
        ]);
        
    $response->assertSessionHasErrors('job_mode');
});

test('can create customer inline via json', function () {
    // CustomerController store policy checks 'create' on Customer model.
    // Frontdesk should have permission. 
    // We might need to ensure 'kaunter' has 'create-customers' or similar permission if it checks distinct permissions.
    // Assuming simple policy or role check.
    
    $response = $this->actingAs($this->frontdesk)
        ->postJson(route('customers.store'), [
            'name' => 'New Customer',
            'phone' => '1234567890',
            'email' => 'test@example.com',
            'job_mode' => 'NORMAL', // just in case
        ]);
        
    $response->assertOk()
        ->assertJson([
            'name' => 'New Customer',
            'phone' => '1234567890',
        ]);
        
    $this->assertDatabaseHas('customers', [
        'email' => 'test@example.com',
    ]);
});
