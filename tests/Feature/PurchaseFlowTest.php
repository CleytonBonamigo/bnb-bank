<?php

use Laravel\Sanctum\Sanctum;
use Turno\Transaction\Enums\TransactionStatus;
use Turno\Transaction\Enums\TransactionType;

beforeEach(function () {
    $this->artisan('db:seed');
});

test('validation for amount and details during purchasing', function () {
    $user = \Turno\Models\User::factory()->create();
    Sanctum::actingAs($user, ['*']);

    $this->json('POST', 'api/purchases')
        ->assertStatus(422)
        ->assertJson([
            'message' => 'The amount field is required. (and 1 more error)',
            'errors'  => [
                'amount' => ['The amount field is required.'],
                'description' => ['The description field is required.'],
            ]
        ]);
});

test('admin restriction during purchasing', function () {
    $admin = \Turno\Models\User::factory()->create(['is_admin' => 1]);
    Sanctum::actingAs($admin, ['*']);

    $data = [
        'amount' => 120.00,
        'description' => 'First purchase'
    ];

    $this->json('POST', 'api/purchases', $data)
         ->assertStatus(403);
});

test('insufficient funds check for purchasing', function () {
    $user = \Turno\Models\User::factory()->create(['balance' => 25.00]);
    Sanctum::actingAs($user, ['*']);

    $data = [
        'amount' => 150.00,
        'description' => 'First purchase'
    ];

    $this->json('POST', 'api/purchases', $data)
        ->assertStatus(422)
        ->assertJson([
            'message' => __('errors.enough_money'),
            'errors'  => [
                'amount' => [__('errors.enough_money')]
            ]
        ]);
});

test('successful purchasing and balance update', function () {
    $user = \Turno\Models\User::factory()->create(['balance' => 50.00]);

    Sanctum::actingAs($user);

    $data = [
        'amount' => 36.79,
        'description' => 'First purchase'
    ];

    $this->json('POST', 'api/purchases', $data)
         ->assertStatus(200)
         ->assertJsonPath('success', true)
         ->assertJsonStructure(['success', 'transaction'])
         ->assertJsonPath('transaction.status_id', TransactionStatus::APPROVED->value)
         ->assertJsonPath('transaction.type_id', TransactionType::PURCHASE->value)
         ->assertJsonPath('transaction.customer_id', $user->id);

    $user2 = \Turno\Models\User::find($user->id);

    expect($user2->balance)->toEqual(13.21);
});
