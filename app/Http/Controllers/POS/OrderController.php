<?php

namespace App\Http\Controllers\POS;

use App\Exceptions\InsufficientStockException;
use App\Http\Controllers\Controller;
use App\Models\DiningTable;
use App\Models\Order;
use App\Models\Product;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $products = Product::where('is_available', true)->get();
        return view('pos.index', compact('products'));
    }

    // Simpan order: harga dihitung server, stok dicek + dikunci, antrian harian
    public function store(Request $request, OrderService $orders)
    {
        $data = $request->validate([
            'cart'           => ['required', 'array', 'min:1', 'max:50'],
            'cart.*.id'      => ['required', 'integer', 'exists:products,id'],
            'cart.*.qty'     => ['required', 'integer', 'min:1', 'max:99'],
            'cart.*.note'    => ['nullable', 'string', 'max:100'],
            'table_number'   => ['nullable', 'string', 'max:20'],
            'customer_name'  => ['nullable', 'string', 'max:100'],
            'order_type'     => ['nullable', 'in:dine_in,takeaway,gofood,grabfood,catering'],
            'payment_method' => ['nullable', 'in:cash,qris,transfer'],
        ]);

        $table = isset($data['table_number'])
            ? DiningTable::where('number', $data['table_number'])->first()
            : null;

        try {
            $order = $orders->create($data['cart'], $data, 'cashier', $table);
        } catch (InsufficientStockException | \DomainException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Throwable $e) {
            report($e); // detail masuk storage/logs/laravel.log, tidak dikirim ke browser
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal memproses transaksi. Coba lagi.',
            ], 500);
        }

        return response()->json([
            'status'       => 'success',
            'message'      => 'Transaksi Berhasil!',
            'order_number' => $order->order_number,
            'queue_number' => $order->queue_number,
        ]);
    }

    // Fungsi Cetak Nota
    public function printInvoice($order_number)
    {
        $order = Order::where('order_number', $order_number)
            ->with('orderItems.product')
            ->firstOrFail();

        return view('pos.print', compact('order'));
    }
}