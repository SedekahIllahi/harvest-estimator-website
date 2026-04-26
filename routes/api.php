<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LandController;
use App\Http\Controllers\Api\UbinanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes (tidak perlu token)
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']); // opsional, hanya untuk testing

// Protected routes (memerlukan token auth:sanctum)
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Land Management (CRUD)
    Route::apiResource('lands', LandController::class);
    // Atau jika ingin eksplisit:
    // Route::get('/lands', [LandController::class, 'index']);
    // Route::post('/lands', [LandController::class, 'store']);
    // Route::get('/lands/{id}', [LandController::class, 'show']);
    // Route::put('/lands/{id}', [LandController::class, 'update']);
    // Route::delete('/lands/{id}', [LandController::class, 'destroy']);

    // Ubinan (Harvest Calculator)
    Route::post('/ubinan/calculate', [UbinanController::class, 'calculate']); // preview without saving
    Route::post('/ubinan', [UbinanController::class, 'store']); // save ubinan record
    Route::get('/ubinan', [UbinanController::class, 'index']); // history semua ubinan milik user
    Route::get('/lands/{landId}/ubinans', [UbinanController::class, 'landHistory']); // history per land

    // Additional: jika ingin hapus atau update ubinan (opsional)
    // Route::delete('/ubinan/{id}', [UbinanController::class, 'destroy']);
    // Route::put('/ubinan/{id}', [UbinanController::class, 'update']);
});