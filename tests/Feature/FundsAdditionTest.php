<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Turno\Models\TransactionCheck;
use Turno\Transaction\Enums\TransactionStatus;
use Turno\Transaction\Enums\TransactionType;

beforeEach(function () {
    $this->artisan('db:seed');
});

test('fields validation during deposit', function () {
    $user = \Turno\Models\User::factory()->create();
    Sanctum::actingAs($user, ['*']);

    $this->json('POST', 'api/deposit')
        ->assertStatus(422)
        ->assertJson([
            'message' => 'The amount field is required. (and 2 more errors)',
            'errors'  => [
                'amount' => ['The amount field is required.'],
                'description' => ['The description field is required.'],
                'check' => ['The check field is required.'],
            ]
        ]);
});

test('admin restriction for deposit', function () {
    $admin = \Turno\Models\User::factory()->create(['is_admin' => 1]);
    Sanctum::actingAs($admin, ['*']);

    Storage::fake('local');
    $file = UploadedFile::fake()->image('receipt.jpg');

    $data = [
        'amount' => 28.59,
        'description' => 'First deposit',
        'check' => $file
    ];

    $this->json('POST', 'api/deposit', $data)
         ->assertStatus(403);
});

test('successful transaction entry', function () {
    $user = \Turno\Models\User::factory()->create();
    Sanctum::actingAs($user, ['*']);
    Storage::fake('local');
    $file = UploadedFile::fake()->image('receipt.jpg');

    $data = [
        'amount' => 28.59,
        'description' => 'First deposit',
        'check' => $file
    ];

    $result = $this->json('POST', 'api/deposit', $data)
       ->assertStatus(200)
       ->assertJsonPath('success', true)
       ->assertJsonStructure(['success', 'transaction'])
       ->assertJsonPath('transaction.status_id', TransactionStatus::PENDING->value)
       ->assertJsonPath('transaction.type_id', TransactionType::DEPOSIT->value)
       ->assertJsonPath('transaction.customer_id', $user->id);

    $transaction_id = $result['transaction']['id'];
    $transaction_check = TransactionCheck::where('transaction_id', $transaction_id)->first();

    Storage::disk('local')->assertExists($transaction_check->url);
});

test('successful receipt retrieval', function () {
    $user = \Turno\Models\User::factory()->create();
    Sanctum::actingAs($user, ['*']);
    Storage::fake('local');
    $file = UploadedFile::fake()->image('receipt.jpg');

    $data = [
        'amount' => 28.59,
        'description' => 'First deposit',
        'check' => $file
    ];

    $result = $this->json('POST', 'api/deposit', $data)
       ->assertStatus(200)
       ->assertJsonPath('success', true)
       ->assertJsonStructure(['success', 'transaction'])
       ->assertJsonPath('transaction.status_id', TransactionStatus::PENDING->value)
       ->assertJsonPath('transaction.type_id', TransactionType::DEPOSIT->value)
       ->assertJsonPath('transaction.customer_id', $user->id);

    $transaction_id = $result['transaction']['id'];

    $this->get("api/transactions/$transaction_id/image")
         ->assertStatus(200);
});
