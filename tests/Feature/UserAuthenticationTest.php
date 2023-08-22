<?php

use Laravel\Sanctum\Sanctum;

test('username and password fields are mandatory', function () {
    $this->json('POST', 'auth/login')
        ->assertStatus(422)
        ->assertJson([
            'message' => 'The username field is required. (and 1 more error)',
            'errors'  => [
                "username" => ['The username field is required.'],
                "password" => ['The password field is required.'],
            ]
        ]);
});

test('invalid userid or passkey provided', function () {
    $credentials = ['username' => 'user', 'password' => 'user'];

    $this->json('POST', 'auth/login', $credentials)
        ->assertStatus(422)
        ->assertJson([
            'message' => 'These credentials do not match our records.',
            'errors'  => [
                'username' => ['These credentials do not match our records.'],
            ]
        ]);
});

test('successful user authentication', function () {
    $user = \Turno\Models\User::factory()->create();

    $credentials = ['username' => $user->username, 'password' => $user->username];

    $this->json('POST', 'auth/login', $credentials)
         ->assertStatus(200)
         ->assertJsonPath('success', true)
         ->assertJsonPath('user.username', $user->username)
         ->assertJsonStructure(['success', 'user', 'token']);
});

test('unsuccessful session termination', function () {
    $this->json('POST', 'auth/logout')
        ->assertStatus(401)
        ->assertJson(['message' => 'Unauthenticated.']);
});

test('successful logout', function () {
    $user = \Turno\Models\User::factory()->create();

    Sanctum::actingAs($user, ['*']);

    $this->json('POST', 'auth/logout')
        ->assertStatus(200)
        ->assertJson(['success' => true]);
});

test('successful logout with token', function () {
    $user  = \Turno\Models\User::factory()->create();
    $token = $user->createToken('authtoken');

    expect($user->tokens()->count())->toEqual(1);

    $this->withHeaders(['Authorization' => "Bearer $token->plainTextToken"])
        ->json('POST', 'auth/logout')
        ->assertStatus(200)
        ->assertJson(['success' => true]);

    expect($user->tokens()->count())->toEqual(0);
});
