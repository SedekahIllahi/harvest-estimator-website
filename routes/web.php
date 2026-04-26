<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UbinanController;
use App\Http\Controllers\AdminFarmerController;
use App\Http\Controllers\LandController;

// --- PUBLIC ROUTES (No login needed) ---
Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/map-test', function () { 
    return view('map-test'); 
});

Route::post('/login', [AuthController::class, 'login']);

// --- PROTECTED ROUTES (Must be logged in) ---
Route::middleware('auth')->group(function () {
    
    // The Sandbox
    Route::get('/sandbox', function () {
        return view('sandbox');
    });

    // Logout (POST for security)
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Farmer Dashboard
    Route::get('/dashboard', function () {
        return view('farmer.dashboard');
    })->name('dashboard');

    // Admin/Dukuh Mapping Page
    Route::get('/admin/pemetaan', function () {
        return view('admin.mapping');
    })->name('admin.mapping');

    // Admin create farmer & land endpoint
    Route::post('/admin/farmers', [AdminFarmerController::class, 'store'])->name('admin.farmers.store');

    // ========== LAND MANAGEMENT (CRUD) ==========
    Route::resource('lands', LandController::class);

    // ========== UBINAN MANAGEMENT (CRUD) ==========
    Route::resource('ubinans', UbinanController::class);
});