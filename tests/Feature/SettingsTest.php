<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('authenticated user can view profile settings page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('pages.settings'));

    $response->assertOk();
    $response->assertSee('Profile Information');
    $response->assertSee('name="name"', false);
    $response->assertSee('name="email"', false);
});

test('authenticated user can update profile information', function () {
    $user = User::factory()->create([
        'name' => 'Old Name',
        'email' => 'old@example.com',
    ]);

    $response = $this->actingAs($user)
        ->from(route('pages.settings'))
        ->post(route('pages.settings.profile'), [
            'name' => 'New Name',
            'email' => 'new@example.com',
        ]);

    $response->assertRedirect(route('pages.settings'));
    $response->assertSessionHas('success', 'Profile information updated successfully!');

    expect($user->fresh()->name)->toBe('New Name');
    expect($user->fresh()->email)->toBe('new@example.com');
});

test('authenticated user can view change password page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('pages.settings.password.show'));

    $response->assertOk();
    $response->assertSee('Update Password');
    $response->assertSee('name="current_password"', false);
});

test('authenticated user can update password', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password123'),
    ]);

    $response = $this->actingAs($user)
        ->from(route('pages.settings.password.show'))
        ->post(route('pages.settings.password'), [
            'current_password' => 'password123',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

    $response->assertRedirect(route('pages.settings.password.show'));
    $response->assertSessionHas('success', 'Password updated successfully!');

    expect(Hash::check('newpassword123', $user->fresh()->password))->toBeTrue();
});

test('authenticated user can view active sessions page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('pages.settings.sessions.show'));

    $response->assertOk();
    $response->assertSee('Browser Sessions');
});

test('authenticated user can view system specs page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('pages.settings.system.show'));

    $response->assertOk();
    $response->assertSee('System & Workspace Information');
    $response->assertSee('Laravel Version');
});
