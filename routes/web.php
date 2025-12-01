<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\POS\OrderController;
use App\Http\Controllers\Kitchen\KitchenDisplayController;
use App\Http\Controllers\Owner\DashboardController;
use App\Http\Controllers\Admin\RestockController;

// 1. Redirect Halaman Depan ke Login
Route::get('/', function () {
    return redirect()->route('login');
});

// --- SEMUA JALUR DI BAWAH INI BUTUH LOGIN ---
Route::middleware('auth')->group(function () {
    
    // A. AREA KASIR / POS
    // Akses: Kasir (Kerja), Owner (Bantu/Pantau), Admin (Pantau)
    Route::middleware('role:cashier,owner,admin')->group(function () {
        Route::get('/pos', [OrderController::class, 'index'])->name('pos.index');
        Route::post('/pos/store', [OrderController::class, 'store'])->name('pos.store');
        Route::get('/pos/print/{order_number}', [OrderController::class, 'printInvoice'])->name('pos.print');
    });

    // B. AREA DAPUR / KDS
    // Akses: Kitchen (Masak), Owner (Pantau), Admin (Pantau)
    Route::middleware('role:kitchen,owner,admin')->group(function () {
        Route::get('/kitchen', [KitchenDisplayController::class, 'index'])->name('kitchen.index');
        Route::get('/kitchen/api/orders', [KitchenDisplayController::class, 'getOrders']);
        Route::post('/kitchen/api/update/{id}', [KitchenDisplayController::class, 'updateStatus']);
    });

    // C. AREA DASHBOARD MANAGEMENT
    // Akses: Owner (Cek Laba/Approve), Admin (Cek Stok)
    Route::middleware('role:owner,admin')->group(function () {
        
        // Dashboard Utama
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Action Approval (Khusus Owner sebenarnya, tapi Admin boleh akses URL ini untuk redirect aman)
        Route::post('/dashboard/approve/{id}', [DashboardController::class, 'approveOrder'])->name('owner.approve');
        Route::post('/dashboard/approve-restock/{id}', [DashboardController::class, 'approveRestock'])->name('owner.approveRestock');
    });

    // D. AREA BELANJA STOK (INPUT)
    // Akses: HANYA ADMIN FINANCE (Sesuai request kamu: Owner tidak boleh input)
    Route::middleware('role:admin')->group(function () {
        Route::get('/restock', [RestockController::class, 'create'])->name('restock.create');
        Route::post('/restock', [RestockController::class, 'store'])->name('restock.store');
    });

});

require __DIR__.'/auth.php';