<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('customer/balance', [\Turno\Customer\Controllers\CustomerController::class, 'balance']);

    Route::get('transactions', [\Turno\Transaction\Controllers\TransactionController::class, 'index']);
    Route::get('transactions', [\Turno\Transaction\Controllers\TransactionController::class, 'index']);
    Route::get('transactions/{transaction_id}', [\Turno\Transaction\Controllers\TransactionController::class, 'show']);
    Route::get('transactions/{transaction_id}/image', [\Turno\Transaction\Controllers\TransactionController::class, 'image']);

    Route::post('deposit/{transaction_id}/approve', [\Turno\DepositManagement\Controllers\DepositManagementController::class, 'approve']);
    Route::post('deposit/{transaction_id}/reject', [\Turno\DepositManagement\Controllers\DepositManagementController::class, 'reject']);

    Route::post('deposit', [\Turno\Deposit\Controllers\DepositController::class, 'store']);
    Route::post('purchases', [\Turno\Purchase\Controllers\PurchaseController::class, 'store']);
});