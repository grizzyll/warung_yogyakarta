<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Recipe;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index()
    {
        $products = Product::where('is_available', true)->get();
        return view('pos.index', compact('products'));
    }

    // FUNGSI BARU: MENYIMPAN ORDER & POTONG STOK
    public function store(Request $request)
    {
        // 1. Validasi data dari Vue
        $request->validate([
            'cart' => 'required|array|min:1', // Keranjang gaboleh kosong
            'total_price' => 'required|numeric',
        ]);

        try {
            // Kita pakai DB Transaction biar aman
            DB::beginTransaction();

            // 2. Buat Nomor Nota (Contoh: ORD-20231128-A1B2)
            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(4));

            // 3. Simpan Header Order
            $order = Order::create([
                'order_number' => $orderNumber,
                'customer_name' => 'Pelanggan Umum', // Nanti bisa diupdate
                'order_type' => 'dine_in', // Default dine-in dulu
                'status' => 'pending', // Masuk ke antrian dapur
                'payment_status' => 'paid',
                'payment_method' => 'cash',
                'total_price' => $request->total_price,
            ]);

            // 4. Simpan Detail Item & POTONG STOK
            foreach ($request->cart as $item) {
                // Simpan item ke tabel order_items
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['qty'],
                    'price' => $item['price'],
                ]);

                // --- LOGIKA POTONG STOK (ALGORITMA AYAM YOGYA) ---
                // Cari resep untuk produk ini
                $recipes = Recipe::where('product_id', $item['id'])->get();

                foreach ($recipes as $recipe) {
                    // Hitung total bahan yang dipakai
                    // Misal: 1 porsi butuh 200gr beras. Beli 2 porsi = 400gr.
                    $totalDeduction = $recipe->amount_needed * $item['qty'];

                    // Kurangi stok di tabel ingredients
                    $ingredient = Ingredient::find($recipe->ingredient_id);
                    if ($ingredient) {
                        $ingredient->decrement('current_stock', $totalDeduction);
                    }
                }
            }

            // Kalau semua lancar, simpan permanen
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Transaksi Berhasil!',
                'order_number' => $orderNumber
            ]);

        } catch (\Exception $e) {
            // Kalau ada error, batalkan semua perubahan database
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memproses transaksi: ' . $e->getMessage()
            ], 500);
        }
    }
}