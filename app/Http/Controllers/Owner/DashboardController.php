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
    public function index(Request $request)
    {
        // 1. FILTER PARAMETERS
        $filter = $request->query('filter', 'week'); // week, month, year
        $chartMode = $request->query('mode', 'profit'); // profit (vs expense) atau growth (vs last year)
        $selectedYear = $request->query('year', Carbon::now()->year);

        $today = Carbon::today();
        $labels = [];

        // Arrays untuk Mode Profit
        $incomeData = [];
        $expenseData = [];
        $profitData = [];

        // Arrays untuk Mode Growth
        $currentData = [];
        $previousData = [];

        // 2. SETUP PERIODE
        if ($filter == 'year') {
            // Tahunan (Bulanan Jan-Des)
            $period = \Carbon\CarbonPeriod::create($selectedYear . '-01-01', '1 month', $selectedYear . '-12-01');
            $grouping = 'monthly';
        } elseif ($filter == 'month') {
            // Bulanan (Harian tgl 1-31)
            $period = \Carbon\CarbonPeriod::create(Carbon::create($selectedYear, Carbon::now()->month, 1), Carbon::create($selectedYear, Carbon::now()->month)->endOfMonth());
            $grouping = 'daily';
        } else {
            // Mingguan (Harian 7 hari terakhir)
            $period = \Carbon\CarbonPeriod::create(Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek());
            $grouping = 'daily';
        }

        // 3. LOOPING DATA (Single Loop Efficient)
        foreach ($period as $date) {
            // FORMAT LABEL
            if ($grouping == 'monthly') {
                if ($date->year > Carbon::now()->year || ($date->year == Carbon::now()->year && $date->month > Carbon::now()->month)) {
                    // Masa depan skip (biar grafik ga turun ke 0 mendadak)
                    $labels[] = $date->format('M');
                    $incomeData[] = null;
                    $expenseData[] = null;
                    $profitData[] = null;
                    $currentData[] = null;
                    $previousData[] = null;
                    continue;
                }
                $labels[] = $date->format('M');

                // Query Bulanan
                $inc = Order::whereYear('created_at', $date->year)->whereMonth('created_at', $date->month)->where('payment_status', 'paid')->sum('total_price');
                $exp = Restock::whereYear('purchase_date', $date->year)->whereMonth('purchase_date', $date->month)->where('status', 'approved')->sum('total_spent');

                // Query Tahun Lalu (Untuk Growth)
                $prevInc = Order::whereYear('created_at', $date->year - 1)->whereMonth('created_at', $date->month)->where('payment_status', 'paid')->sum('total_price');

            } else {
                // Query Harian
                $labels[] = $date->format('d M');
                $fDate = $date->format('Y-m-d');

                $inc = Order::whereDate('created_at', $fDate)->where('payment_status', 'paid')->sum('total_price');
                $exp = Restock::whereDate('purchase_date', $fDate)->where('status', 'approved')->sum('total_spent');

                // Query Hari/Minggu Lalu (Untuk Growth)
                $prevDate = $filter == 'month' ? $date->copy()->subMonth() : $date->copy()->subWeek();
                $prevInc = Order::whereDate('created_at', $prevDate)->where('payment_status', 'paid')->sum('total_price');
            }

            // Push ke Array Profit
            $incomeData[] = $inc;
            $expenseData[] = $exp;
            $profitData[] = $inc - $exp;

            // Push ke Array Growth
            $currentData[] = $inc;
            $previousData[] = $prevInc;
        }

        // 4. DATA LAINNYA (Summary Cards)
        $incomeTotal = Order::whereYear('created_at', $selectedYear)->where('payment_status', 'paid')->sum('total_price');
        $expenseTotal = Restock::whereYear('purchase_date', $selectedYear)->where('status', 'approved')->sum('total_spent');
        $netProfit = $incomeTotal - $expenseTotal;
        $dailyRevenue = Order::whereDate('created_at', Carbon::today())->where('payment_status', 'paid')->sum('total_price');
        $dailyCount = Order::whereDate('created_at', Carbon::today())->count();

        // Data Approval & Stok
        $lowStocks = Ingredient::whereColumn('current_stock', '<=', 'stock_alert')->get();
        $pendingApprovals = Order::where('status', 'waiting_approval')->with('orderItems.product')->get();
        $pendingRestocks = Restock::where('status', 'pending')->with(['supplier', 'items.ingredient'])->get();

        return view('owner.dashboard', compact(
            'filter',
            'chartMode',
            'selectedYear',
            'labels',
            'incomeData',
            'expenseData',
            'profitData', // Data Profit Mode
            'currentData',
            'previousData', // Data Growth Mode
            'incomeTotal',
            'expenseTotal',
            'netProfit',
            'dailyRevenue',
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
            foreach ($restock->items as $item) {
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