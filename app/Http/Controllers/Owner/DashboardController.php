<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. DATA KEUANGAN (Harian & Bulanan)
        $today = Carbon::today();
        $thisMonth = Carbon::now()->month;

        // Omzet Hari Ini
        $dailyRevenue = Order::whereDate('created_at', $today)
            ->where('payment_status', 'paid')
            ->sum('total_price');

        // Omzet Bulan Ini
        $monthlyRevenue = Order::whereMonth('created_at', $thisMonth)
            ->where('payment_status', 'paid')
            ->sum('total_price');

        // Total Transaksi Hari Ini
        $dailyCount = Order::whereDate('created_at', $today)->count();

        // 2. DATA STOK KRITIS (Low Stock Alert)
        // Ambil bahan yang stoknya DI BAWAH batas alert
        $lowStocks = Ingredient::whereColumn('current_stock', '<=', 'stock_alert')->get();

        // 3. APPROVAL CATERING / PESANAN BESAR
        // Cari orderan catering yang statusnya masih 'pending' (Belum di-ACC)
        // Anggap pesanan > 500rb atau tipe 'catering' butuh approval
        $pendingApprovals = Order::where('status', 'pending')
            ->where(function($q) {
                $q->where('order_type', 'catering')
                  ->orWhere('total_price', '>=', 500000); // Atau nominal besar
            })
            ->with('orderItems.product')
            ->get();

        return view('owner.dashboard', compact(
            'dailyRevenue', 
            'monthlyRevenue', 
            'dailyCount', 
            'lowStocks', 
            'pendingApprovals'
        ));
    }

    // Fungsi untuk menyetujui Pesanan Besar
    public function approveOrder($id)
    {
        $order = Order::find($id);
        if ($order) {
            $order->status = 'cooking'; // Langsung kirim ke dapur
            $order->save();
            return back()->with('success', 'Pesanan Catering #' . $order->order_number . ' Telah Disetujui!');
        }
        return back()->with('error', 'Pesanan tidak ditemukan');
    }
}