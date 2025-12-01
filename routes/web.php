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
   // HANYA OWNER yang boleh masuk sini
Route::middleware('role:owner')->group(function () { 
    
    // Dashboard Utama
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Action Approval
    Route::post('/dashboard/approve/{id}', [DashboardController::class, 'approveOrder'])->name('owner.approve');
    Route::post('/dashboard/approve-restock/{id}', [DashboardController::class, 'approveRestock'])->name('owner.approveRestock');
});

    // D. AREA ADMIN (FINANCE & STOCK)
    Route::middleware('role:admin')->group(function () {
        
        // Restock (Yang lama)
        Route::get('/restock', [RestockController::class, 'create'])->name('restock.create');
        Route::post('/restock', [RestockController::class, 'store'])->name('restock.store');

        // Finance Dashboard (BARU)
        Route::get('/finance', [App\Http\Controllers\Admin\FinanceController::class, 'index'])->name('finance.index');
    });

});

require __DIR__.'/auth.php';