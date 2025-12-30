<?php

use App\Models\Customer;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'pentadbiran']);
    $this->technician = User::factory()->create(['role' => 'juruteknik']);
});

test('admin can view customers index', function () {
    Customer::factory()->count(3)->create();

    $response = $this->actingAs($this->admin)->get(route('customers.index'));

    $response->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Customers/Index')
            ->has('customers.data', 3)
        );
});

test('technician can view customers index', function () {
    $response = $this->actingAs($this->technician)->get(route('customers.index'));

    $response->assertStatus(200);
});

test('customers can be searched', function () {
    Customer::factory()->create(['name' => 'John Doe', 'phone' => '0123456789']);
    Customer::factory()->create(['name' => 'Jane Smith', 'phone' => '0198765432']);

    $response = $this->actingAs($this->admin)->get(route('customers.index', ['search' => 'John']));

    $response->assertInertia(fn (Assert $page) => $page
        ->has('customers.data', 1)
        ->where('customers.data.0.name', 'John Doe')
    );
});

test('admin can view customer details', function () {
    $customer = Customer::factory()->create();

    $response = $this->actingAs($this->admin)->get(route('customers.show', $customer));

    $response->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Customers/Show')
            ->has('customer')
            ->where('customer.id', $customer->id)
        );
});

test('admin can create customer', function () {
    $data = [
        'name' => 'Test Customer',
        'email' => 'customer@example.com',
        'phone' => '0123456789',
        'address' => '123 Test Street',
        'department' => 'IT Department',
        'government_entity' => 'Ministry of Finance',
    ];

    $response = $this->actingAs($this->admin)->post(route('customers.store'), $data);

    $response->assertRedirect();
    $this->assertDatabaseHas('customers', [
        'name' => 'Test Customer',
        'email' => 'customer@example.com',
        'phone' => '0123456789',
    ]);
});

test('technician can create customer', function () {
    $data = [
        'name' => 'Walk-in Customer',
        'phone' => '0199887766',
    ];

    $response = $this->actingAs($this->technician)->post(route('customers.store'), $data);

    $response->assertRedirect();
    $this->assertDatabaseHas('customers', [
        'name' => 'Walk-in Customer',
    ]);
});

test('customer creation requires name and phone', function () {
    $response = $this->actingAs($this->admin)->post(route('customers.store'), []);

    $response->assertSessionHasErrors(['name', 'phone']);
});

test('customer email must be unique', function () {
    Customer::factory()->create(['email' => 'existing@example.com']);

    $data = [
        'name' => 'Another Customer',
        'email' => 'existing@example.com',
        'phone' => '0123456789',
    ];

    $response = $this->actingAs($this->admin)->post(route('customers.store'), $data);

    $response->assertSessionHasErrors('email');
});

test('admin can update customer', function () {
    $customer = Customer::factory()->create();

    $data = [
        'name' => 'Updated Name',
        'phone' => '0111222333',
    ];

    $response = $this->actingAs($this->admin)->put(route('customers.update', $customer), $data);

    $response->assertRedirect();
    $this->assertDatabaseHas('customers', [
        'id' => $customer->id,
        'name' => 'Updated Name',
        'phone' => '0111222333',
    ]);
});

test('technician can update customer', function () {
    $customer = Customer::factory()->create();

    $data = [
        'address' => 'New Address',
    ];

    $response = $this->actingAs($this->technician)->put(route('customers.update', $customer), $data);

    $response->assertRedirect();
});

test('admin can delete customer', function () {
    $customer = Customer::factory()->create();

    $response = $this->actingAs($this->admin)->delete(route('customers.destroy', $customer));

    $response->assertRedirect();
    $this->assertSoftDeleted('customers', ['id' => $customer->id]);
});

test('technician cannot delete customer', function () {
    $customer = Customer::factory()->create();

    $response = $this->actingAs($this->technician)->delete(route('customers.destroy', $customer));

    $response->assertStatus(403);
});

test('customer search autocomplete returns json', function () {
    Customer::factory()->create(['name' => 'ABC Company']);
    Customer::factory()->create(['name' => 'XYZ Corporation']);

    $response = $this->actingAs($this->admin)->get(route('customers.search', ['q' => 'ABC']));

    $response->assertStatus(200)
        ->assertJsonStructure([
            '*' => ['id', 'name', 'phone'],
        ])
        ->assertJsonCount(1);
});
