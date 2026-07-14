<?php

test('guests are redirected from the dashboard to the login page', function () {
    $this->get('/')->assertRedirect('/login');
});

test('the login page renders for guests', function () {
    $this->get('/login')->assertOk();
});

test('authenticated users can view components showcase page', function () {
    $user = \App\Models\User::factory()->create();

    $this->actingAs($user)->get(route('pages.components'))
        ->assertOk()
        ->assertSee('UI Component Library');
});

test('authenticated users can view error pages preview catalog', function () {
    $user = \App\Models\User::factory()->create();

    $this->actingAs($user)->get(route('pages.errors-preview'))
        ->assertOk()
        ->assertSee('HTTP Error Previews');
});

test('authenticated users can preview individual error pages', function () {
    $user = \App\Models\User::factory()->create();

    $this->actingAs($user)->get(route('pages.errors-preview.show', ['code' => 404]))
        ->assertStatus(404)
        ->assertSee('Page Not Found');

    $this->actingAs($user)->get(route('pages.errors-preview.show', ['code' => 403]))
        ->assertStatus(403);
});
