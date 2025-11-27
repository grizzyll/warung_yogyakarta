<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    // --- TAMBAHKAN FUNGSI INI ---
    public function product()
    {
        // Satu Item punya SATU Produk
        return $this->belongsTo(Product::class);
    }
    // ----------------------------
}