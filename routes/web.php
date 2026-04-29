<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UbinanController;
use App\Http\Controllers\AdminFarmerController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminLandController;

use App\Http\Controllers\LandController;

// --- PUBLIC ROUTES (No login needed) ---
Route::get('/', function () { return view('welcome'); });
Route::get('/login', function () { return view('auth.login'); })->name('login');
Route::get('/map-test', function () { 
    return view('map-test'); 
});
Route::post('/login', [AuthController::class, 'login']);

// --- PROTECTED ROUTES (Logged in users: Farmers AND Admins) ---
Route::middleware('auth')->group(function () {
    
    Route::get('/sandbox', function () { return view('sandbox'); });
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', function () {
        return view('farmer.dashboard');
    })->name('dashboard');

    Route::post('/ubinans', [UbinanController::class, 'store'])->name('ubinans.store');
});

// --- ADMIN STRICT ROUTES (Admins ONLY) ---
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // URL: /admin/dashboard | Route Name: admin.dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // URL: /admin/tambah-petani | Route Name: admin.register-farmer
    Route::get('/tambah-petani', function () { return view('admin.register-farmer'); })->name('register-farmer');

    // URL: /admin/farmers | Route Name: admin.farmers.store
    Route::post('/farmers', [AdminFarmerController::class, 'store'])->name('farmers.store');

    // The Farmer Management List
    Route::get('/farmers', [AdminFarmerController::class, 'index'])->name('farmers.index');
    
    // The existing store route you made
    Route::post('/farmers', [AdminFarmerController::class, 'store'])->name('farmers.store');
    
    // Edit & Update
    Route::get('/farmers/{farmer}/edit', [AdminFarmerController::class, 'edit'])->name('farmers.edit');
    Route::put('/farmers/{farmer}', [AdminFarmerController::class, 'update'])->name('farmers.update');
    
    // Quick Password Reset Fallback
    Route::post('/farmers/{farmer}/reset-pin', [AdminFarmerController::class, 'resetPin'])->name('farmers.reset-pin');
    
    // Delete / Deactivate
    Route::delete('/farmers/{farmer}', [AdminFarmerController::class, 'destroy'])->name('farmers.destroy');

    // The Master Map View
    Route::get('/mapping', [App\Http\Controllers\AdminLandController::class, 'index'])->name('mapping');

    // Land Management Actions
    Route::put('/lands/{land}', [App\Http\Controllers\AdminLandController::class, 'update'])->name('lands.update');
    Route::delete('/lands/{land}', [App\Http\Controllers\AdminLandController::class, 'destroy'])->name('lands.destroy');

    // Ubinan & Harvest Estimates
    Route::get('/ubinans', [\App\Http\Controllers\UbinanController::class, 'index'])->name('ubinans.index');
    Route::post('/ubinans', [\App\Http\Controllers\UbinanController::class, 'store'])->name('ubinans.store');
    Route::patch('/ubinans/{ubinan}/status', [\App\Http\Controllers\UbinanController::class, 'updateStatus'])->name('ubinans.update-status');
});