<?php

use App\Models\Customer;
use App\Models\JobNote;
use App\Models\User;
use App\Models\WorkshopJob;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'pentadbiran']);
    $this->technician = User::factory()->create(['role' => 'juruteknik']);
    $this->customer = Customer::factory()->create();
    $this->job = WorkshopJob::factory()->create(['customer_id' => $this->customer->id]);
});

test('admin can create job note', function () {
    $data = [
        'content' => 'This is a test note',
        'is_public' => true,
        'note_type' => 'general',
    ];

    $response = $this->actingAs($this->admin)->post(route('jobs.notes.store', $this->job), $data);

    $response->assertRedirect();
    $this->assertDatabaseHas('job_notes', [
        'workshop_job_id' => $this->job->id,
        'user_id' => $this->admin->id,
        'content' => 'This is a test note',
        'is_public' => true,
    ]);
});

test('technician can create job note', function () {
    $data = [
        'content' => 'Diagnostic complete',
        'is_public' => false,
        'note_type' => 'diagnostic',
    ];

    $response = $this->actingAs($this->technician)->post(route('jobs.notes.store', $this->job), $data);

    $response->assertRedirect();
    $this->assertDatabaseHas('job_notes', [
        'workshop_job_id' => $this->job->id,
        'user_id' => $this->technician->id,
        'content' => 'Diagnostic complete',
    ]);
});

test('note creation requires content', function () {
    $response = $this->actingAs($this->admin)->post(route('jobs.notes.store', $this->job), []);

    $response->assertSessionHasErrors('content');
});

test('admin can update any note', function () {
    $note = JobNote::factory()->create([
        'workshop_job_id' => $this->job->id,
        'user_id' => $this->technician->id,
        'content' => 'Original content',
    ]);

    $data = [
        'content' => 'Updated content',
    ];

    $response = $this->actingAs($this->admin)->put(route('jobs.notes.update', [$this->job, $note]), $data);

    $response->assertRedirect();
    $this->assertDatabaseHas('job_notes', [
        'id' => $note->id,
        'content' => 'Updated content',
    ]);
});

test('technician can update own note', function () {
    $note = JobNote::factory()->create([
        'workshop_job_id' => $this->job->id,
        'user_id' => $this->technician->id,
        'content' => 'Original content',
    ]);

    $data = [
        'content' => 'My updated content',
    ];

    $response = $this->actingAs($this->technician)->put(route('jobs.notes.update', [$this->job, $note]), $data);

    $response->assertRedirect();
    $this->assertDatabaseHas('job_notes', [
        'id' => $note->id,
        'content' => 'My updated content',
    ]);
});

test('technician cannot update other users note', function () {
    $otherTechnician = User::factory()->create(['role' => 'juruteknik']);

    $note = JobNote::factory()->create([
        'workshop_job_id' => $this->job->id,
        'user_id' => $otherTechnician->id,
        'content' => 'Original content',
    ]);

    $data = [
        'content' => 'Trying to update',
    ];

    $response = $this->actingAs($this->technician)->put(route('jobs.notes.update', [$this->job, $note]), $data);

    $response->assertStatus(403);
});

test('admin can delete any note', function () {
    $note = JobNote::factory()->create([
        'workshop_job_id' => $this->job->id,
        'user_id' => $this->technician->id,
    ]);

    $response = $this->actingAs($this->admin)->delete(route('jobs.notes.destroy', [$this->job, $note]));

    $response->assertRedirect();
    $this->assertSoftDeleted('job_notes', ['id' => $note->id]);
});

test('technician can delete own note', function () {
    $note = JobNote::factory()->create([
        'workshop_job_id' => $this->job->id,
        'user_id' => $this->technician->id,
    ]);

    $response = $this->actingAs($this->technician)->delete(route('jobs.notes.destroy', [$this->job, $note]));

    $response->assertRedirect();
    $this->assertSoftDeleted('job_notes', ['id' => $note->id]);
});

test('technician cannot delete other users note', function () {
    $otherTechnician = User::factory()->create(['role' => 'juruteknik']);

    $note = JobNote::factory()->create([
        'workshop_job_id' => $this->job->id,
        'user_id' => $otherTechnician->id,
    ]);

    $response = $this->actingAs($this->technician)->delete(route('jobs.notes.destroy', [$this->job, $note]));

    $response->assertStatus(403);
});

test('admin can view all notes including private', function () {
    JobNote::factory()->create([
        'workshop_job_id' => $this->job->id,
        'user_id' => $this->technician->id,
        'is_public' => true,
    ]);

    JobNote::factory()->create([
        'workshop_job_id' => $this->job->id,
        'user_id' => $this->technician->id,
        'is_public' => false,
    ]);

    $response = $this->actingAs($this->admin)->get(route('jobs.notes.index', $this->job));

    $response->assertStatus(200)
        ->assertJsonCount(2, 'notes');
});

test('note can have different types', function () {
    $types = ['general', 'diagnostic', 'repair', 'parts', 'customer_communication'];

    foreach ($types as $type) {
        $data = [
            'content' => "Note of type {$type}",
            'note_type' => $type,
        ];

        $response = $this->actingAs($this->admin)->post(route('jobs.notes.store', $this->job), $data);

        $response->assertRedirect();
        $this->assertDatabaseHas('job_notes', [
            'workshop_job_id' => $this->job->id,
            'note_type' => $type,
        ]);
    }
});
