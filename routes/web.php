<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UbinanController;
use App\Http\Controllers\AdminFarmerController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminLandController;
use App\Http\Controllers\LandController;

// --- PUBLIC ROUTES ---
Route::get('/', function () { return view('welcome'); });
Route::get('/login', function () { return view('auth.login'); })->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/map-test', function () { return view('map-test'); });

// --- FARMER ROUTES (Logged in users only) ---
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', function () { return view('farmer.dashboard'); })->name('dashboard');
    Route::get('/sandbox', function () { return view('sandbox'); });

    // Farmer's Ubinan Input
    Route::post('/ubinans', [UbinanController::class, 'store'])->name('ubinans.store');

    // Friend's Land Controller (Restricted to Farmers)
    Route::resource('lands', LandController::class); 
});

// --- ADMIN STRICT ROUTES (God Mode) ---
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Farmer Management
    Route::get('/tambah-petani', function () { return view('admin.register-farmer'); })->name('register-farmer');
    Route::get('/farmers', [AdminFarmerController::class, 'index'])->name('farmers.index');
    Route::post('/farmers', [AdminFarmerController::class, 'store'])->name('farmers.store');
    Route::get('/farmers/{farmer}/edit', [AdminFarmerController::class, 'edit'])->name('farmers.edit');
    Route::put('/farmers/{farmer}', [AdminFarmerController::class, 'update'])->name('farmers.update');
    Route::post('/farmers/{farmer}/reset-pin', [AdminFarmerController::class, 'resetPin'])->name('farmers.reset-pin');
    Route::delete('/farmers/{farmer}', [AdminFarmerController::class, 'destroy'])->name('farmers.destroy');

    // Map & Land Management (YOUR CONTROLLER)
    Route::get('/mapping', [AdminLandController::class, 'index'])->name('mapping');
    Route::put('/lands/{land}', [AdminLandController::class, 'update'])->name('lands.update');
    Route::delete('/lands/{land}', [AdminLandController::class, 'destroy'])->name('lands.destroy');

    // Ubinan Admin Actions
    Route::get('/ubinans', [UbinanController::class, 'index'])->name('ubinans.index');
    Route::post('/ubinans', [UbinanController::class, 'store'])->name('ubinans.store');
    Route::patch('/ubinans/{ubinan}/status', [UbinanController::class, 'updateStatus'])->name('ubinans.update-status');
});