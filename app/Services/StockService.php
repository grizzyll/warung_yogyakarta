<?php

namespace App\Services;

use App\Exceptions\InsufficientStockException;
use App\Models\Ingredient;
use Illuminate\Support\Collection;

class StockService
{
    /**
     * Cek lalu potong stok. WAJIB dipanggil di dalam DB::transaction.
     *
     * @param Collection $lines  isi: ['product' => Product (recipes ter-load), 'qty' => int]
     */
    public function deduct(Collection $lines): void
    {
        $need = [];    // ingredient_id => total kebutuhan
        $usedBy = [];  // ingredient_id => [nama menu => true]

        foreach ($lines as $line) {
            foreach ($line['product']->recipes as $recipe) {
                $id = $recipe->ingredient_id;
                $need[$id] = ($need[$id] ?? 0) + ((float) $recipe->amount_needed * $line['qty']);
                $usedBy[$id][$line['product']->name] = true;
            }
        }

        if (empty($need)) {
            return; // menu tanpa resep: tidak ada yang dipotong
        }

        // Kunci baris bahan, urut id supaya dua transaksi tidak saling deadlock
        $ingredients = Ingredient::whereIn('id', array_keys($need))
            ->orderBy('id')
            ->lockForUpdate()
            ->get()
            ->keyBy('id');

        // Cek SEMUA dulu, baru potong
        $short = [];
        foreach ($need as $id => $amount) {
            $ing = $ingredients->get($id);
            if (!$ing || (float) $ing->current_stock + 1e-9 < $amount) {
                $short = array_merge($short, array_keys($usedBy[$id]));
            }
        }
        if ($short) {
            throw new InsufficientStockException(array_values(array_unique($short)));
        }

        foreach ($need as $id => $amount) {
            $ingredients->get($id)->decrement('current_stock', $amount);
        }
    }
}