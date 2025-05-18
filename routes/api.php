<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\IuranController;
use App\Http\Controllers\Api\KritikController;
use App\Http\Controllers\Api\MutationController;
use App\Http\Controllers\Api\PayIuranController;
use App\Http\Controllers\Api\ProkerController;
use App\Http\Controllers\Api\RtController;
use App\Http\Controllers\Api\RwController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\WargaController;

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

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('reset-password', 'resetPassword');
    Route::post('change-password', 'changePassword');
});

Route::controller(UserController::class)->group(function () {
    Route::post('request', 'createUser');
});
Route::middleware('auth:sanctum')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('logout', 'logout');
    });
    Route::controller(UserController::class)->group(function () {
        Route::post('activate', 'activateUser');
        Route::get('me', 'me');
        Route::put('me', 'profileUpdate');
    });
    Route::apiResource('warga', WargaController::class);
    Route::apiResource('rw', RwController::class);
    Route::apiResource('rt', RtController::class);
    Route::apiResource('proker', ProkerController::class);
    Route::apiResource('kritik', KritikController::class);
    Route::apiResource('iuran/pay', PayIuranController::class)->only(['index', 'store', 'update']);
    Route::apiResource('iuran', IuranController::class);
    Route::apiResource('mutasi', MutationController::class);
    Route::apiResource('wallet', WalletController::class)->only(['index', 'store']);
});
