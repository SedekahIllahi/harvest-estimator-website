<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UbinanController;
use App\Http\Controllers\AdminFarmerController;

// --- PUBLIC ROUTES (No login needed) ---
Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/map-test', function () { return view('map-test'); });

Route::post('/login', [AuthController::class, 'login']);

// --- PROTECTED ROUTES (Must be logged in to see these) ---
Route::middleware('auth')->group(function () {
    
    // The Sandbox
    Route::get('/sandbox', function () {
        return view('sandbox');
    });

    // Logout MUST be a POST request for security
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // 1. The Farmer Dashboard (Missing from your file!)
    Route::get('/dashboard', function () {
        return view('farmer.dashboard');
    })->name('dashboard');

    // 2. The Admin/Dukuh Dashboard (Missing from your file!)
    Route::get('/admin/pemetaan', function () {
        return view('admin.mapping');
    })->name('admin.mapping');

    // 3. The Ubinan Save Endpoint
    Route::post('/ubinans', [UbinanController::class, 'store'])->name('ubinans.store');

    // 4. The Admin Farmer + Land Creation Endpoint
    Route::post('/admin/farmers', [AdminFarmerController::class, 'store'])->name('admin.farmers.store');
});