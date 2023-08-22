<?php

use Laravel\Sanctum\Sanctum;
use Turno\Models\Transaction;
use Turno\Transaction\Enums\TransactionStatus;
use Turno\Transaction\Enums\TransactionType;

beforeEach(function () {
    $this->artisan('db:seed');
});

test('should show my individual transaction', function () {
    $user = \Turno\Models\User::factory()->create();
    Sanctum::actingAs($user, ['*']);

    $transaction = Transaction::create([
        'status_id' => TransactionStatus::APPROVED->value,
        'type_id' => TransactionType::PURCHASE->value,
        'customer_id' => $user->id,
        'amount' => -50.99,
        'description' => 'First order',
    ]);

    $this->json('GET', "api/transactions/{$transaction->id}")
        ->assertStatus(200)
        ->assertJsonPath('success', true)
        ->assertJsonStructure(['success', 'transaction'])
        ->assertJsonPath('transaction.status_id', TransactionStatus::APPROVED->value)
        ->assertJsonPath('transaction.type_id', TransactionType::PURCHASE->value)
        ->assertJsonPath('transaction.customer_id', $user->id);
});

test('should not allow viewing of transactions from other customers', function () {
    $user = \Turno\Models\User::factory()->create();
    Sanctum::actingAs($user, ['*']);

    $transaction = Transaction::create([
        'status_id' => TransactionStatus::APPROVED->value,
        'type_id' => TransactionType::PURCHASE->value,
        'customer_id' => \Turno\Models\User::where('is_admin', false)->where('id', '!=', $user->id)->inRandomOrder()->first()->id,
        'amount' => 87.44,
        'description' => 'First deposit',
    ]);

    $this->json('GET', "api/transactions/{$transaction->id}")
        ->assertStatus(403);
});

test('admin must be able to view a transaction', function () {
    $admin = \Turno\Models\User::factory()->create(['is_admin' => true]);
    Sanctum::actingAs($admin, ['*']);
    $userId = \Turno\Models\User::where('is_admin', false)->inRandomOrder()->first()->id;

    $transaction = Transaction::create([
        'status_id' => TransactionStatus::APPROVED->value,
        'type_id' => TransactionType::PURCHASE->value,
        'customer_id' => $userId,
        'amount' => 67.75,
        'description' => 'First deposit',
    ]);

    $this->json('GET', "api/transactions/{$transaction->id}")
        ->assertStatus(200)
        ->assertJsonPath('success', true)
        ->assertJsonStructure(['success', 'transaction'])
        ->assertJsonPath('transaction.status_id', TransactionStatus::APPROVED->value)
        ->assertJsonPath('transaction.type_id', TransactionType::PURCHASE->value)
        ->assertJsonPath('transaction.customer_id', $userId);
});

test('should show all my transactions in a list', function () {
    $user = \Turno\Models\User::factory()->create();
    Sanctum::actingAs($user, ['*']);

    \Turno\Models\Transaction::factory(2)->create(['type_id' => TransactionType::DEPOSIT->value, 'customer_id' => $user->id]);
    \Turno\Models\Transaction::factory(3)->create(['type_id' => TransactionType::PURCHASE->value, 'customer_id' => $user->id]);

    //No filters
    $this->json('GET', 'api/transactions')
        ->assertStatus(200)
        ->assertJsonPath('success', true)
        ->assertJsonStructure(['success', 'data'])
        ->assertJsonCount(5, 'data')
        ->assertJsonPath('data.0.customer_id', $user->id);
});

test('must present my list of transactions filtered by types', function () {
    $user = \Turno\Models\User::factory()->create();
    Sanctum::actingAs($user, ['*']);

    $types = [TransactionType::DEPOSIT->value => 2, TransactionType::PURCHASE->value => 3];

    foreach ($types as $type => $number) {
        \Turno\Models\Transaction::factory($number)->create([
            'type_id'     => $type,
            'customer_id' => $user->id
        ]);
    }

    foreach ($types as $type => $number) {
        $this->json('GET', 'api/transactions', ['type_id' => $type])
            ->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure(['success', 'data'])
            ->assertJsonCount($number, 'data')
            ->assertJsonPath('data.0.customer_id', $user->id)
            ->assertJsonPath('data.0.type_id', $type);
    }
});

test('should display my transactions list based on status filters', function () {
    $user = \Turno\Models\User::factory()->create();
    Sanctum::actingAs($user, ['*']);

    $status = [
        TransactionStatus::PENDING->value => 2,
        TransactionStatus::APPROVED->value => 3,
        TransactionStatus::REJECTED->value => 1
    ];

    foreach ($status as $key => $number) {
        \Turno\Models\Transaction::factory($number)->create([
            'status_id'   => $key,
            'customer_id' => $user->id
        ]);
    }

    foreach ($status as $key => $number) {
        $this->json('GET', 'api/transactions', ['status_id' => $key])
            ->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure(['success', 'data'])
            ->assertJsonCount($number, 'data')
            ->assertJsonPath('data.0.customer_id', $user->id)
            ->assertJsonPath('data.0.status_id', $key);
    }
});

test('must display even if there are no transactions', function () {
    $user = \Turno\Models\User::factory()->create();
    Sanctum::actingAs($user, ['*']);

    $this->json('GET', 'api/transactions')
        ->assertStatus(200)
        ->assertJsonPath('success', true)
        ->assertJsonStructure(['success', 'data'])
        ->assertJsonCount(0, 'data');
});
