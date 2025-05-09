<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProkerController;
use App\Http\Controllers\Api\RtController;
use App\Http\Controllers\Api\RwController;
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
});

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('logout', 'logout');
        Route::get('me', 'me');
    });
    Route::apiResource('warga', WargaController::class);
    Route::apiResource('rw', RwController::class);
    Route::apiResource('rt', RtController::class);
    Route::apiResource('proker', ProkerController::class);
});
