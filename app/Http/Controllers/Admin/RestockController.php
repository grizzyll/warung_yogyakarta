<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restock;
use App\Models\RestockItem;
use App\Models\Ingredient;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RestockController extends Controller
{
    // 1. Tampilkan Form Belanja
    public function create()
    {
        $suppliers = Supplier::all();
        $ingredients = Ingredient::all();
        return view('admin.restock.create', compact('suppliers', 'ingredients'));
    }

    // 2. Proses Simpan & Tambah Stok
   public function store(Request $request)
    {
        // Validasi... (sama seperti sebelumnya)
        $request->validate([
            'supplier_id' => 'required',
            'purchase_date' => 'required|date',
            'items' => 'required|array',
        ]);

        try {
            DB::beginTransaction();

            // 1. Hitung Total Belanja
            $grandTotal = 0;
            foreach($request->items as $item) {
                $grandTotal += $item['price'] * $item['qty'];
            }

            // 2. Tentukan Status & Batas Nominal (Misal: 1 Juta)
            $limitApproval = 1000000; 
            
            // Jika total > 1 juta, status PENDING. Jika kecil, APPROVED.
            $status = ($grandTotal > $limitApproval) ? 'pending' : 'approved';

            // 3. Simpan Header
            $restock = Restock::create([
                'invoice_number' => $request->invoice_number ?? 'INV-'.time(),
                'supplier_id' => $request->supplier_id,
                'total_spent' => $grandTotal,
                'purchase_date' => $request->purchase_date,
                'user_id' => Auth::id(),
                'status' => $status, // <--- Kolom Baru
            ]);

            // 4. Simpan Detail Item
            foreach($request->items as $ingId => $data) {
                if($data['qty'] > 0) {
                    RestockItem::create([
                        'restock_id' => $restock->id,
                        'ingredient_id' => $ingId,
                        'quantity' => $data['qty'],
                        'price_per_unit' => $data['price'],
                        'subtotal' => $data['qty'] * $data['price'],
                    ]);

                    // 5. UPDATE STOK (HANYA JIKA STATUS APPROVED)
                    // Kalau pending, stok JANGAN ditambah dulu!
                    if ($status === 'approved') {
                        $ingredient = Ingredient::find($ingId);
                        $ingredient->increment('current_stock', $data['qty']);
                    }
                }
            }

            DB::commit();

            // Pesan Balikan Beda-beda
            if ($status === 'pending') {
                return redirect()->route('dashboard')->with('success', 'Belanjaan Besar (Rp '.number_format($grandTotal).') Berhasil Disimpan! Menunggu Approval Owner agar stok masuk.');
            } else {
                return redirect()->route('dashboard')->with('success', 'Stok berhasil ditambahkan!');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal restock: ' . $e->getMessage());
        }
    }
}