<?php

use App\Models\SampleItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('authenticated user can view sample items index page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('pages.sample-items'));

    $response->assertOk();
    $response->assertSee('Sample Items');
    $response->assertSee('Add Sample Item');
});

test('authenticated user can fetch sample items datatable data via ajax', function () {
    $user = User::factory()->create();
    SampleItem::create(['name' => 'Item Alpha', 'description' => 'Desc A', 'status' => 'active']);
    SampleItem::create(['name' => 'Item Beta', 'description' => 'Desc B', 'status' => 'inactive']);

    $response = $this->actingAs($user)
        ->getJson(route('pages.sample-items', ['draw' => 1, 'start' => 0, 'length' => 10]), [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);

    $response->assertOk()
        ->assertJsonStructure([
            'draw',
            'success',
            'message',
            'data' => [
                'data',
                'pagination',
            ],
        ]);

    $data = $response->json('data.data');
    expect($data)->toHaveCount(2);
    expect($data[0]['name'])->toBe('Item Alpha');
});

test('authenticated user can view create sample item page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('pages.sample-items.create'));

    $response->assertOk();
    $response->assertSee('Create Sample Item');
    $response->assertSee('name="name"', false);
    $response->assertSee('name="status"', false);
});

test('authenticated user can store a new sample item', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->postJson(route('pages.sample-items.store'), [
            'name' => 'New Boilerplate Item',
            'description' => 'Testing description',
            'status' => 'active',
        ]);

    $response->assertStatus(201)
        ->assertJsonPath('success', true)
        ->assertJsonPath('message', 'Item created successfully!');

    $this->assertDatabaseHas('sample_items', [
        'name' => 'New Boilerplate Item',
        'status' => 'active',
    ]);
});

test('store validation fails for invalid fields', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->postJson(route('pages.sample-items.store'), [
            'name' => '', // required
            'status' => 'invalid_status', // active or inactive
        ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['name', 'status']);
});

test('authenticated user can view edit sample item page', function () {
    $user = User::factory()->create();
    $item = SampleItem::create(['name' => 'Edit Me', 'status' => 'active']);

    $response = $this->actingAs($user)->get(route('pages.sample-items.edit', $item->id));

    $response->assertOk();
    $response->assertSee('Edit Sample Item');
    $response->assertSee('Edit Me');
});

test('authenticated user can update a sample item', function () {
    $user = User::factory()->create();
    $item = SampleItem::create(['name' => 'Old Name', 'status' => 'active']);

    $response = $this->actingAs($user)
        ->putJson(route('pages.sample-items.update', $item->id), [
            'name' => 'Updated Name',
            'status' => 'inactive',
        ]);

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('message', 'Item updated successfully!');

    $this->assertDatabaseHas('sample_items', [
        'id' => $item->id,
        'name' => 'Updated Name',
        'status' => 'inactive',
    ]);
});

test('authenticated user can toggle status of a sample item', function () {
    $user = User::factory()->create();
    $item = SampleItem::create(['name' => 'Status Toggle', 'status' => 'active']);

    $response = $this->actingAs($user)
        ->patchJson(route('pages.sample-items.toggle', $item->id));

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.status', 'inactive');

    $this->assertDatabaseHas('sample_items', [
        'id' => $item->id,
        'status' => 'inactive',
    ]);
});

test('authenticated user can delete a sample item', function () {
    $user = User::factory()->create();
    $item = SampleItem::create(['name' => 'Delete Me', 'status' => 'active']);

    $response = $this->actingAs($user)
        ->deleteJson(route('pages.sample-items.destroy', $item->id));

    $response->assertOk()
        ->assertJsonPath('success', true);

    $this->assertDatabaseMissing('sample_items', [
        'id' => $item->id,
    ]);
});
