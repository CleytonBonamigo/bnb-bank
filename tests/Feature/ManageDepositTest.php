<?php

use Laravel\Sanctum\Sanctum;
use Turno\Models\Transaction;
use Turno\Transaction\Enums\TransactionStatus;
use Turno\Transaction\Enums\TransactionType;

beforeEach(function () {
    $this->artisan('db:seed');
});

test('only admins can approve deposits', function () {
    $user = \Turno\Models\User::factory()->create();
    Sanctum::actingAs($user, ['*']);

    $entry = Transaction::factory()->create([
        'status_id' => TransactionStatus::PENDING->value,
        'type_id'   => TransactionType::DEPOSIT->value
    ]);

    $this->json('POST', "api/deposit/{$entry->id}/approve")
         ->assertStatus(403);
});

test('transaction approval should fail if not found', function () {
    $adminUser = \Turno\Models\User::factory()->create(['is_admin' => 1]);
    Sanctum::actingAs($adminUser, ['*']);

    $this->json('POST', "api/deposit/9999/approve")
        ->assertStatus(404)
        ->assertJsonPath('success', false)
        ->assertJsonStructure(['success', 'message'])
        ->assertJsonPath('message', __('errors.transaction_not_found'));
});

test('transaction approval should fail if already approved', function () {
    $adminUser = \Turno\Models\User::factory()->create(['is_admin' => 1]);
    Sanctum::actingAs($adminUser, ['*']);

    $entry = Transaction::factory()->create([
        'status_id' => TransactionStatus::APPROVED->value,
        'type_id'   => TransactionType::DEPOSIT->value
    ]);

    $this->json('POST', "api/deposit/{$entry->id}/approve")
        ->assertStatus(422)
        ->assertJsonPath('success', false)
        ->assertJsonStructure(['success', 'message'])
        ->assertJsonPath('message', __('errors.transaction_previously_approved'));
});

test('transaction approval should fail if already denied', function () {
    $adminUser = \Turno\Models\User::factory()->create(['is_admin' => 1]);
    Sanctum::actingAs($adminUser, ['*']);

    $entry = Transaction::factory()->create([
        'status_id' => TransactionStatus::REJECTED->value,
        'type_id'   => TransactionType::DEPOSIT->value
    ]);

    $this->json('POST', "api/deposit/{$entry->id}/approve")
         ->assertStatus(422)
         ->assertJsonPath('success', false)
         ->assertJsonStructure(['success', 'message'])
         ->assertJsonPath('message', __('errors.transaction_previously_rejected'));
});

test('transaction should be approved successfully', function () {
    $adminUser = \Turno\Models\User::factory()->create(['is_admin' => 1]);
    Sanctum::actingAs($adminUser, ['*']);

    $entry = Transaction::factory()->create([
        'status_id' => TransactionStatus::PENDING->value,
        'type_id'   => TransactionType::DEPOSIT->value
    ]);

    $customer_balance = $entry->customer->balance;

    $this->json('POST', "api/deposit/$entry->id/approve")
         ->assertStatus(200)
         ->assertJsonPath('success', true);

    $transaction2 = \Turno\Models\Transaction::find($entry->id);

    expect($transaction2->customer->balance)->toEqual($customer_balance + $entry->amount);
    expect($transaction2->status_id)->toEqual(TransactionStatus::APPROVED->value);
});

test('transaction rejection should fail for non-admins', function () {
    $user = \Turno\Models\User::factory()->create();
    Sanctum::actingAs($user, ['*']);

    $entry = Transaction::factory()->create([
        'status_id' => TransactionStatus::PENDING->value,
        'type_id'   => TransactionType::DEPOSIT->value
    ]);

    $this->json('POST', "api/deposit/{$entry->id}/reject")
         ->assertStatus(403);
});

test('transaction rejection should fail if not found', function () {
    $adminUser = \Turno\Models\User::factory()->create(['is_admin' => 1]);
    Sanctum::actingAs($adminUser, ['*']);

    $this->json('POST', "api/deposit/9999/reject")
         ->assertStatus(404)
         ->assertJsonPath('success', false)
         ->assertJsonStructure(['success', 'message'])
         ->assertJsonPath('message', __('errors.transaction_not_found'));
});

test('transaction rejection should fail if already approved', function () {
    $adminUser = \Turno\Models\User::factory()->create(['is_admin' => 1]);
    Sanctum::actingAs($adminUser, ['*']);

    $entry = Transaction::factory()->create([
        'status_id' => TransactionStatus::APPROVED->value,
        'type_id'   => TransactionType::DEPOSIT->value
    ]);

    $this->json('POST', "api/deposit/{$entry->id}/reject")
         ->assertStatus(422)
         ->assertJsonPath('success', false)
         ->assertJsonStructure(['success', 'message'])
         ->assertJsonPath('message', __('errors.transaction_previously_approved'));
});

test('transaction rejection should fail if already denied', function () {
    $adminUser = \Turno\Models\User::factory()->create(['is_admin' => 1]);
    Sanctum::actingAs($adminUser, ['*']);

    $entry = Transaction::factory()->create([
        'status_id' => TransactionStatus::REJECTED->value,
        'type_id'   => TransactionType::DEPOSIT->value
    ]);

    $this->json('POST', "api/deposit/{$entry->id}/reject")
         ->assertStatus(422)
         ->assertJsonPath('success', false)
         ->assertJsonStructure(['success', 'message'])
         ->assertJsonPath('message', __('errors.transaction_previously_rejected'));
});

test('transaction rejection succeeds', function () {
    $adminUser = \Turno\Models\User::factory()->create(['is_admin' => 1]);
    Sanctum::actingAs($adminUser, ['*']);

    $entry = Transaction::factory()->create([
        'status_id' => TransactionStatus::PENDING->value,
        'type_id'   => TransactionType::DEPOSIT->value
    ]);

    $customer_balance = $entry->customer->balance;

    $this->json('POST', "api/deposit/{$entry->id}/reject")
         ->assertStatus(200)
         ->assertJsonPath('success', true);

    $transaction2 = \Turno\Models\Transaction::find($entry->id);

    expect($transaction2->customer->balance)->toEqual($customer_balance);
    expect($transaction2->status_id)->toEqual(TransactionStatus::REJECTED->value);
});
