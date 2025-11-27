<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
     use HasFactory;

    protected $guarded = [];

    // --- TAMBAHKAN FUNGSI INI ---
    public function orderItems()
    {
        // Satu Order punya BANYAK Item
        return $this->hasMany(OrderItem::class);
    }
}
