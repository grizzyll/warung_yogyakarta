<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\DiningTable;
use App\Models\Product;

class OrderController extends Controller
{
    public function show(string $token)
    {
        $table = DiningTable::where('token', $token)
            ->where('is_active', true)
            ->firstOrFail();

        // Hanya kolom yang perlu tampil. Resep dan stok tidak boleh bocor ke pelanggan.
        $products = Product::where('is_available', true)
            ->orderBy('category')
            ->orderBy('name')
            ->get(['id', 'name', 'category', 'price', 'image']);

        return view('customer.menu', compact('table', 'products'));
    }
}