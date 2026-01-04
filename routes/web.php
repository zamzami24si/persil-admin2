<?php
// routes/web.php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WargaController;
use App\Http\Controllers\PersilController;
use App\Http\Controllers\DokumenPersilController;
use App\Http\Controllers\PetaPersilController;
use App\Http\Controllers\SengketaPersilController;
use App\Http\Controllers\JenisPenggunaanController;
use App\Http\Controllers\UserController;

// ====================
// PUBLIC ROUTES (Tidak perlu login)
// ====================

// Routes untuk auth (tidak perlu middleware)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

// Routes untuk register (tidak perlu middleware)
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('auth.register');
Route::post('/register', [AuthController::class, 'register'])->name('auth.register.post');

// ====================
// PROTECTED ROUTES (Perlu login)
// ====================

// Group route yang membutuhkan login
Route::middleware(['checkislogin'])->group(function () {

    // Logout (harus login dulu)
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

    // Dashboard - bisa diakses semua role yang sudah login
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD Warga - semua user yang login bisa akses
    Route::resource('warga', WargaController::class);

    // CRUD Persil - semua user yang login bisa akses
    Route::resource('persil', PersilController::class);

    // ===== PERSIL MEDIA ROUTES =====
  Route::prefix('persil')->group(function () {
    // GANTI NAMA ROUTE DARI 'persil.download' KE 'persil.download-file'
    Route::get('/{mediaId}/download', [PersilController::class, 'downloadFile'])->name('persil.download-file');
    Route::get('/{mediaId}/preview', [PersilController::class, 'previewFile'])->name('persil.preview-file');
});

    // CRUD Jenis Penggunaan - semua user yang login bisa akses
    Route::resource('jenis-penggunaan', JenisPenggunaanController::class);

    // Dokumen Persil - semua user yang login bisa akses
    Route::prefix('dokumen-persil')->group(function () {
        Route::get('/', [DokumenPersilController::class, 'index'])->name('dokumen-persil.index');
        Route::get('/create/{persil_id}', [DokumenPersilController::class, 'create'])->name('dokumen-persil.create');
        Route::post('/store/{persil_id}', [DokumenPersilController::class, 'store'])->name('dokumen-persil.store');
        Route::get('/{id}', [DokumenPersilController::class, 'show'])->name('dokumen-persil.show');
        Route::get('/{id}/edit', [DokumenPersilController::class, 'edit'])->name('dokumen-persil.edit');
        Route::put('/{id}', [DokumenPersilController::class, 'update'])->name('dokumen-persil.update');
        Route::delete('/{id}', [DokumenPersilController::class, 'destroy'])->name('dokumen-persil.destroy');

        // ===== DOKUMEN PERSIL MEDIA ROUTES =====
        Route::get('/{mediaId}/download', [DokumenPersilController::class, 'downloadFile'])->name('dokumen-persil.download');
        Route::get('/{mediaId}/preview', [DokumenPersilController::class, 'previewFile'])->name('dokumen-persil.preview');
    });

    // Peta Persil - semua user yang login bisa akses
    Route::prefix('peta-persil')->group(function () {
        Route::get('/', [PetaPersilController::class, 'index'])->name('peta-persil.index');
        Route::get('/create/{persil_id}', [PetaPersilController::class, 'create'])->name('peta-persil.create');
        Route::post('/store/{persil_id}', [PetaPersilController::class, 'store'])->name('peta-persil.store');
        Route::get('/{id}', [PetaPersilController::class, 'show'])->name('peta-persil.show');
        Route::get('/{id}/edit', [PetaPersilController::class, 'edit'])->name('peta-persil.edit');
        Route::put('/{id}', [PetaPersilController::class, 'update'])->name('peta-persil.update');
        Route::delete('/{id}', [PetaPersilController::class, 'destroy'])->name('peta-persil.destroy');

        // ===== PETA PERSIL MEDIA ROUTES =====
        Route::get('/{mediaId}/download', [PetaPersilController::class, 'downloadFile'])->name('peta-persil.download');
        Route::get('/{mediaId}/preview', [PetaPersilController::class, 'previewFile'])->name('peta-persil.preview');
    });

    // Sengketa Persil - semua user yang login bisa akses
    Route::prefix('sengketa-persil')->group(function () {
        Route::get('/', [SengketaPersilController::class, 'index'])->name('sengketa-persil.index');
        Route::get('/create/{persil_id}', [SengketaPersilController::class, 'create'])->name('sengketa-persil.create');
        Route::post('/store/{persil_id}', [SengketaPersilController::class, 'store'])->name('sengketa-persil.store');
        Route::get('/{id}', [SengketaPersilController::class, 'show'])->name('sengketa-persil.show');
        Route::get('/{id}/edit', [SengketaPersilController::class, 'edit'])->name('sengketa-persil.edit');
        Route::put('/{id}', [SengketaPersilController::class, 'update'])->name('sengketa-persil.update');
        Route::delete('/{id}', [SengketaPersilController::class, 'destroy'])->name('sengketa-persil.destroy');

        // ===== SENGKETA PERSIL MEDIA ROUTES =====
        Route::get('/{mediaId}/download', [SengketaPersilController::class, 'downloadFile'])->name('sengketa-persil.download');
        Route::get('/{mediaId}/preview', [SengketaPersilController::class, 'previewFile'])->name('sengketa-persil.preview');

        // Legacy route untuk kompatibilitas (jika ada yang masih menggunakan route lama)
        Route::get('/{id}/download-bukti', [SengketaPersilController::class, 'downloadBukti'])->name('sengketa-persil.download-bukti');
    });

    // User Management - hanya untuk admin
    Route::middleware(['checkrole:super_admin'])->prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/', [UserController::class, 'store'])->name('users.store');
        Route::get('/{id}', [UserController::class, 'show'])->name('users.show');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('users.destroy');

        // ===== USER MEDIA ROUTES =====
        Route::delete('/{id}/delete-foto', [UserController::class, 'deleteFotoProfil'])->name('users.delete-foto');
        Route::put('/{id}/verify', [UserController::class, 'verify'])->name('users.verify');
    });
});
