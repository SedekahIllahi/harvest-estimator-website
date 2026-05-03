<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UbinanController;
use App\Http\Controllers\AdminFarmerController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminLandController;
use App\Http\Controllers\AdminPriceController; // [TAMBAH] Import controller price

// Farmer Controllers
use App\Http\Controllers\FarmerDashboardController;
use App\Http\Controllers\FarmerLandController;
use App\Http\Controllers\FarmerProfileController;
use App\Http\Controllers\FarmerKalkulatorController;
use App\Http\Controllers\FarmerRiwayatController;

// --- PUBLIC ROUTES ---
Route::get('/', function () { return view('welcome'); });
Route::get('/login', function () { return view('auth.login'); })->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/map-test', function () { return view('map-test'); });

// [TAMBAH] API untuk mengambil data price (digunakan kalkulator farmer)
Route::get('/api/prices', function () {
    return App\Models\Price::all(['slug', 'commodity', 'price_per_kg', 'conversion_factor']);
})->name('api.prices');

// --- FARMER ROUTES (Login required) ---
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Route untuk farmer dashboard (dengan controller)
    Route::get('/dashboard', [FarmerDashboardController::class, 'index'])->name('farmer.dashboard');

    // Sandbox (jika masih dipakai)
    Route::get('/sandbox', function () { return view('sandbox'); });

    // Ubinan input untuk petani
    Route::post('/ubinans', [UbinanController::class, 'store'])->name('ubinans.store');

    // Manajemen lahan milik petani sendiri (menggunakan FarmerLandController)
    Route::resource('my-lands', FarmerLandController::class)->names([
        'index' => 'farmer.lands.index',
        'show' => 'farmer.lands.show',
        'edit' => 'farmer.lands.edit',
        'update' => 'farmer.lands.update',
    ]);

    // Profile petani
    Route::get('/profile', [FarmerProfileController::class, 'index'])->name('farmer.profile');
    Route::get('/profile/edit', [FarmerProfileController::class, 'edit'])->name('farmer.profile.edit');               // [TAMBAH]
    Route::put('/profile', [FarmerProfileController::class, 'update'])->name('farmer.profile.update');
    Route::get('/profile/pin', [FarmerProfileController::class, 'showPinForm'])->name('farmer.profile.pin.form');   // [TAMBAH]
    Route::put('/profile/pin', [FarmerProfileController::class, 'updatePin'])->name('farmer.profile.pin');

    // Kalkulator dan Riwayat
    Route::get('/kalkulator', [FarmerKalkulatorController::class, 'index'])->name('farmer.kalkulator');
    Route::get('/riwayat', [FarmerRiwayatController::class, 'index'])->name('farmer.riwayat');
});

// --- ADMIN ROUTES (God Mode) ---
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Manajemen Petani
    Route::get('/tambah-petani', function () { return view('admin.register-farmer'); })->name('register-farmer');
    Route::get('/farmers', [AdminFarmerController::class, 'index'])->name('farmers.index');
    Route::post('/farmers', [AdminFarmerController::class, 'store'])->name('farmers.store');
    Route::get('/farmers/{farmer}/edit', [AdminFarmerController::class, 'edit'])->name('farmers.edit');
    Route::put('/farmers/{farmer}', [AdminFarmerController::class, 'update'])->name('farmers.update');
    Route::post('/farmers/{farmer}/reset-pin', [AdminFarmerController::class, 'resetPin'])->name('farmers.reset-pin');
    Route::delete('/farmers/{farmer}', [AdminFarmerController::class, 'destroy'])->name('farmers.destroy');

    // Manajemen Lahan (oleh admin)
    Route::get('/mapping', [AdminLandController::class, 'index'])->name('mapping');
    Route::put('/lands/{land}', [AdminLandController::class, 'update'])->name('lands.update');
    Route::delete('/lands/{land}', [AdminLandController::class, 'destroy'])->name('lands.destroy');

    // Ubinan admin
    Route::get('/ubinans', [UbinanController::class, 'index'])->name('ubinans.index');
    Route::post('/ubinans', [UbinanController::class, 'store'])->name('ubinans.store');
    Route::patch('/ubinans/{ubinan}/status', [UbinanController::class, 'updateStatus'])->name('ubinans.update-status');

    // [TAMBAH] Route untuk update harga komoditas (Price)
    Route::put('/prices/{price}', [AdminPriceController::class, 'update'])->name('prices.update');
});