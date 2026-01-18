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
            // ... validasi di atas ...

            DB::beginTransaction();

            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(4));

            // --- LOGIKA PEMISAHAN ---
            // Cek: Apakah ini Catering ATAU Harganya di atas 10 JUTA?
            $isLargeOrder = ($request->order_type === 'catering' || $request->total_price >= 10000000);

            // Kalau BESAR -> Status 'waiting_approval' (Dapur GAK LIHAT, Owner LIHAT)
            // Kalau KECIL -> Status 'pending' (Dapur LANGSUNG LIHAT)
            $initialStatus = $isLargeOrder ? 'waiting_approval' : 'pending';

            $order = Order::create([
                'order_number' => $orderNumber,
                'table_number' => $request->table_number,
                'customer_name' => $request->customer_name ?? 'Pelanggan Umum',
                'order_type' => $request->order_type ?? 'dine_in',
                'status' => $initialStatus, // <--- PENTING!
                'payment_status' => 'paid',
                'payment_method' => 'cash',
                'total_price' => $request->total_price,
            ]);

            // ... simpan order items di bawahnya ...

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
    // Fungsi Cetak Nota
    public function printInvoice($order_number)
    {
        // Cari order berdasarkan nomor nota, sekalian ambil item & produknya
        $order = Order::where('order_number', $order_number)
            ->with('orderItems.product')
            ->firstOrFail();

        return view('pos.print', compact('order'));
    }
}
