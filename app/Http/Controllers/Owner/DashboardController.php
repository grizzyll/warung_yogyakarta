<?php
namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Ingredient;
use App\Models\Restock; // <--- INI SERING LUPA!
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

        // Omzet Hari Ini (Hanya yang sudah bayar)
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
        // Cari orderan yang statusnya pending DAN (tipe catering ATAU total > 500rb)
           $pendingApprovals = Order::where('status', 'waiting_approval') 
            ->with('orderItems.product')
            ->get();
        // 4. APPROVAL BELANJA STOK (Fitur Baru)
        // Cari belanjaan admin yang statusnya pending
        $pendingRestocks = Restock::where('status', 'pending')
            ->with(['supplier', 'items.ingredient']) // Load detail supplier & bahan
            ->get();

        // Kirim semua variabel ke View
        return view('owner.dashboard', compact(
            'dailyRevenue', 
            'monthlyRevenue', 
            'dailyCount', 
            'lowStocks', 
            'pendingApprovals', 
            'pendingRestocks'
        ));
    }

    // Fungsi: Owner menyetujui Belanjaan Admin (Restock)
    public function approveRestock($id)
    {
        $restock = Restock::with('items')->find($id);

        if ($restock && $restock->status === 'pending') {
            
            // A. Ubah Status jadi Approved
            $restock->status = 'approved';
            $restock->save();

            // B. MASUKKAN STOK KE GUDANG SEKARANG
            foreach($restock->items as $item) {
                $ingredient = Ingredient::find($item->ingredient_id);
                // Tambah stok saat ini dengan jumlah beli
                $ingredient->increment('current_stock', $item->quantity);
            }

            return back()->with('success', 'Pembelian Stok Disetujui! Stok telah ditambahkan.');
        }

        return back()->with('error', 'Data tidak ditemukan atau sudah diproses.');
    }

    // Fungsi: Owner menyetujui Pesanan Besar (Catering)
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