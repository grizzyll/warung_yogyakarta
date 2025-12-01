<?php

namespace App\Http\Controllers\Kitchen;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class KitchenDisplayController extends Controller
{
    // 1. Tampilkan Halaman Utama Dapur
    public function index()
    {
        return view('kitchen.index');
    }

    // 2. API: Ambil Data Order (Dipanggil tiap 5 detik oleh Vue)
    public function getOrders()
    {
        // Hanya ambil Pending (Kecil/Sudah ACC) dan Cooking
        // Waiting_approval GAK BAKAL KEMBIL
        $orders = Order::with('orderItems.product')
            ->whereIn('status', ['pending', 'cooking']) 
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($orders);
    }

    // 3. API: Update Status (Pending -> Cooking -> Ready)
    public function updateStatus(Request $request, $id)
    {
        $order = Order::find($id);
        
        if ($order) {
            $order->status = $request->status;
            $order->save();
            return response()->json(['message' => 'Status updated']);
        }

        return response()->json(['message' => 'Order not found'], 404);
    }
}