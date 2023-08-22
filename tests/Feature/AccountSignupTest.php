<?php

test('validation for mandatory fields during sign up', function () {
    $this->json('POST', 'auth/register')
        ->assertStatus(422)
        ->assertJson([
            'message' => 'The name field is required. (and 4 more errors)',
            'errors'  => [
                'name' => ['The name field is required.'],
                'email' => ['The email field is required.'],
                'username' => ['The username field is required.'],
                'password' => ['The password field is required.'],
                'password_confirmation' => ['The password confirmation field is required.'],
            ]
        ]);
});

test('invalid mail format during sign up', function () {
    $credentials = [
        'name' => 'User',
        'email' => 'user@',
        'username' => 'user',
        'password' => 'user',
        'password_confirmation' => 'user'
    ];

    $this->json('POST', 'auth/register', $credentials)
        ->assertStatus(422)
        ->assertJson([
            'message' => 'The email must be a valid email address.',
            'errors'  => [
                'email' => ['The email must be a valid email address.'],
            ]
        ]);
});

test('user_id or mail already exists in the records', function () {
    $user = \Turno\Models\User::factory()->create([
        'email' => 'user@user.com',
        'username' => 'user',
    ]);

    $credentials = [
        'name' => 'User',
        'email' => 'user@user.com',
        'username' => 'user',
        'password' => 'user',
        'password_confirmation' => 'user'
    ];

    $this->json('POST', 'auth/register', $credentials)
        ->assertStatus(422)
        ->assertJson([
            'message' => 'The email has already been taken. (and 1 more error)',
            'errors'  => [
                'email'    => ['The email has already been taken.'],
                'username' => ['The username has already been taken.'],
            ]
        ]);
});

test('password confirmation mismatch during sign up', function () {
    $credentials = [
        'name' => 'User',
        'email' => 'user@user.com',
        'username' => 'user',
        'password' => 'user',
        'password_confirmation' => 'user1'
    ];

    $this->json('POST', 'auth/register', $credentials)
        ->assertStatus(422)
        ->assertJson([
            'message' => 'The password confirmation does not match.',
            'errors'  => [
                'password' => ['The password confirmation does not match.'],
            ]
        ]);
});

test('successful account creation', function () {
    $credentials = [
        'name' => 'User',
        'email' => 'user@user.com',
        'username' => 'user',
        'password' => 'user',
        'password_confirmation' => 'user'
    ];

    $this->json('POST', 'auth/register', $credentials)
         ->assertStatus(200)
         ->assertJsonPath('success', true)
         ->assertJsonPath('user.username', 'user');
});
