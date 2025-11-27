<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\POS\OrderController;
use App\Http\Controllers\Kitchen\KitchenDisplayController;
use App\Http\Controllers\ProfileController;

// Halaman Depan (Login)
Route::get('/', function () {
    return redirect()->route('login');
});

// --- JALUR YANG BUTUH LOGIN (SEMUA PEGAWAI) ---
Route::middleware('auth')->group(function () {
    
    // 1. Area KASIR (Hanya boleh Kasir & Owner)
    Route::middleware('role:cashier,owner')->group(function () {
        Route::get('/pos', [OrderController::class, 'index'])->name('pos.index');
        Route::post('/pos/store', [OrderController::class, 'store'])->name('pos.store');
    });

    // 2. Area DAPUR (Hanya boleh Staff Dapur & Owner)
    // (Nanti kamu perlu buat user role 'kitchen' di database)
    Route::middleware('role:kitchen,owner')->group(function () {
        Route::get('/kitchen', [KitchenDisplayController::class, 'index'])->name('kitchen.index');
        Route::get('/kitchen/api/orders', [KitchenDisplayController::class, 'getOrders']);
        Route::post('/kitchen/api/update/{id}', [KitchenDisplayController::class, 'updateStatus']);
    });

    // 3. Area OWNER & ADMIN (Laporan, Keuangan)
    Route::middleware('role:owner,admin')->prefix('dashboard')->group(function () {
        // Nanti kita buat Controller Dashboard khusus Owner di sini
        Route::get('/', function () {
            return "INI HALAMAN RAHASIA OWNER (GRAFIK KEUNTUNGAN)";
        })->name('owner.dashboard');
    });
    // 3. Area OWNER & ADMIN (Laporan, Keuangan)
    Route::middleware('role:owner,admin')->prefix('dashboard')->group(function () {
        
        // Halaman Utama Dashboard
        Route::get('/', [App\Http\Controllers\Owner\DashboardController::class, 'index'])
            ->name('dashboard'); // <--- GANTI JADI INI (Hapus 'owner.')

        // Action Approve Catering
        Route::post('/approve/{id}', [App\Http\Controllers\Owner\DashboardController::class, 'approveOrder'])
            ->name('owner.approve');
            
    });
});

require __DIR__.'/auth.php';
