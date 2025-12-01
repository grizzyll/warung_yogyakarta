<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestockItem extends Model
{
    protected $guarded = [];

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }
}
