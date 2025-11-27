<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat User Admin & Kasir
        DB::table('users')->insert([
            [
                'name' => 'Pak Owner',
                'email' => 'owner@ayam.com',
                'password' => Hash::make('password'),
                'role' => 'owner',
            ],
            [
                'name' => 'Mba Kasir',
                'email' => 'kasir@ayam.com',
                'password' => Hash::make('password'),
                'role' => 'cashier',
            ]
        ]);

        // 2. Buat Supplier
        $supplierId = DB::table('suppliers')->insertGetId([
            'name' => 'UD. Ayam Segar Jaya',
            'contact_person' => 'Pak Budi',
            'phone' => '08123456789',
            'category' => 'Ayam',
            'address' => 'Pasar Besar Blok A1',
            'created_at' => now(), 'updated_at' => now(),
        ]);

        // 3. Buat Bahan Baku (Ingredients)
        $ayamMentahId = DB::table('ingredients')->insertGetId([
            'name' => 'Ayam Potong (Mentah)',
            'unit' => 'ekor',
            'stock_alert' => 10,
            'current_stock' => 50, // Stok Awal 50
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $berasId = DB::table('ingredients')->insertGetId([
            'name' => 'Beras Premium',
            'unit' => 'gram',
            'stock_alert' => 5000,
            'current_stock' => 25000,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        // 4. Buat Produk Menu (Products)
        $menuAyamId = DB::table('products')->insertGetId([
            'name' => 'Paket Ayam Bakar Yogya',
            'category' => 'Makanan',
            'price' => 25000,
            'is_available' => true,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $menuNasiId = DB::table('products')->insertGetId([
            'name' => 'Nasi Putih',
            'category' => 'Makanan',
            'price' => 5000,
            'is_available' => true,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        // 5. Buat Resep (Product -> Ingredient)
        DB::table('recipes')->insert([
            [
                'product_id' => $menuAyamId,
                'ingredient_id' => $ayamMentahId,
                'amount_needed' => 1,
            ],
            [
                'product_id' => $menuNasiId,
                'ingredient_id' => $berasId,
                'amount_needed' => 150,
            ]
        ]);
    }
}