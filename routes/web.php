<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::post('auth/login', [\Turno\Auth\Controllers\LoginController::class, 'login']);
Route::post('auth/logout', [\Turno\Auth\Controllers\LoginController::class, 'logout'])->middleware('auth:sanctum');
Route::post('auth/register', [\Turno\Auth\Controllers\RegisterController::class, 'store']);

Route::get('{any}', function () {
    return view('index');
})->where('any', '.*');
