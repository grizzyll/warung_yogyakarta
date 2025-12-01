<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Restock;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function index()
    {
        // 1. Ambil Pemasukan (Order yang sudah dibayar)
        $incomes = Order::where('payment_status', 'paid')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($order) {
                return [
                    'date' => $order->created_at,
                    'type' => 'in', // Masuk
                    'desc' => 'Penjualan #' . $order->order_number,
                    'amount' => $order->total_price,
                    'status' => 'success'
                ];
            });

        // 2. Ambil Pengeluaran (Restock yang sudah diapprove/lunas)
        $expenses = Restock::where('status', 'approved') // Hanya yang sudah ACC
            ->orderBy('purchase_date', 'desc')
            ->get()
            ->map(function ($restock) {
                return [
                    'date' => $restock->created_at, // atau purchase_date
                    'type' => 'out', // Keluar
                    'desc' => 'Belanja Stok (' . $restock->supplier->name . ')',
                    'amount' => $restock->total_spent,
                    'status' => 'approved'
                ];
            });

        // 3. Ambil Pengeluaran Pending (Untuk info saja)
        $pendingExpenses = Restock::where('status', 'pending')
            ->get()
            ->map(function ($restock) {
                return [
                    'date' => $restock->created_at,
                    'type' => 'out',
                    'desc' => 'Belanja Pending (' . $restock->supplier->name . ')',
                    'amount' => $restock->total_spent,
                    'status' => 'pending'
                ];
            });

        // 4. Gabung Semua & Urutkan berdasarkan Tanggal Terbaru
        $transactions = $incomes->merge($expenses)->merge($pendingExpenses)->sortByDesc('date');

        // 5. Hitung Total
        $totalIncome = $incomes->sum('amount');
        $totalExpense = $expenses->sum('amount');
        $balance = $totalIncome - $totalExpense;

        return view('admin.finance.index', compact('transactions', 'totalIncome', 'totalExpense', 'balance'));
    }
}