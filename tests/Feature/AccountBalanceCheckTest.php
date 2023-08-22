<?php

use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->artisan('db:seed');
});

test('administrators should not view their own account balance', function () {
    $user = \Turno\Models\User::factory()->create(['is_admin' => true]);
    Sanctum::actingAs($user, ['*']);

    $this->json('GET', 'api/customer/balance')
         ->assertStatus(403);
});

test('account holders should be able to see their balance', function () {
    $balance = 250;
    $user = \Turno\Models\User::factory()->create(['balance' => $balance]);
    Sanctum::actingAs($user, ['*']);

    $this->json('GET', 'api/customer/balance')
         ->assertStatus(200)
         ->assertJsonPath('success', true)
         ->assertJsonStructure(['success', 'balance'])
         ->assertJsonPath('balance', $balance);
});
