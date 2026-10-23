<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DiningTable extends Model
{
    protected $fillable = ['number', 'token', 'status', 'capacity', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    protected static function booted(): void
    {
        // token UUID dibuat otomatis, tidak pernah dari nomor meja
        static::creating(function (DiningTable $t) {
            $t->token ??= (string) Str::uuid();
        });
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}