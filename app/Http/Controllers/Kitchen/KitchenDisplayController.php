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
        // Ambil order yang statusnya 'pending' (Baru) atau 'cooking' (Sedang dimasak)
        // Urutkan dari yang terlama (biar yang pesan duluan dimasak duluan)
        $orders = Order::with('orderItems.product') // Ambil detail item & nama produk
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