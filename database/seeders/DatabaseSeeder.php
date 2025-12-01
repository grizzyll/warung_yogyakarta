<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat User (Kalau sudah ada logikanya bisa skip/tetap ditimpa)
        // ... (User Admin/Owner/Kasir tetap sama seperti sebelumnya) ...
        DB::table('users')->insert([
            ['name' => 'Felisitas Owner', 'email' => 'owner@ayam.com', 'password' => Hash::make('password'), 'role' => 'owner'],
            ['name' => 'Mba Kasir', 'email' => 'kasir@ayam.com', 'password' => Hash::make('password'), 'role' => 'cashier'],
            ['name' => 'Mas Admin', 'email' => 'admin@ayam.com', 'password' => Hash::make('password'), 'role' => 'admin'],
            ['name' => 'Dapur', 'email' => 'dapur@ayam.com', 'password' => Hash::make('password'), 'role' => 'kitchen'],
        ]);

        // 2. Buat Supplier & Bahan Baku (Wajib ada biar stok jalan)
        $supplierId = DB::table('suppliers')->insertGetId([
            'name' => 'Pasar Besar Jaya', 'contact_person' => 'Pak Budi', 'phone' => '08123', 'category' => 'Umum',
            'created_at' => now(), 'updated_at' => now()
        ]);

        $ayamId = DB::table('ingredients')->insertGetId(['name' => 'Ayam Mentah', 'unit' => 'ekor', 'stock_alert' => 10, 'current_stock' => 100, 'created_at' => now(), 'updated_at' => now()]);
        $berasId = DB::table('ingredients')->insertGetId(['name' => 'Beras', 'unit' => 'kg', 'stock_alert' => 5, 'current_stock' => 50, 'created_at' => now(), 'updated_at' => now()]);
        $tehId = DB::table('ingredients')->insertGetId(['name' => 'Teh Tubruk', 'unit' => 'pack', 'stock_alert' => 2, 'current_stock' => 20, 'created_at' => now(), 'updated_at' => now()]);

        // 3. INPUT MENU (PRODUCTS) DENGAN GAMBAR
        $products = [
            // MAKANAN
            [
                'name' => '1 ekor Ayam Goreng Kremes', 
                'category' => 'Makanan', 
                'price' => 90000, 
                'image' => '1 ekor ayam goreng.webp', // <--- Pastikan nama file sama persis
                'is_available' => true
            ],
            [
                'name' => '1 ekor Ayam Bakar', 
                'category' => 'Makanan', 
                'price' => 90000, 
                'image' => '1 ekor ayam bakar.webp', 
                'is_available' => true
            ],
            [
                'name' => '1 bakul nasi putih(5 porsi)', 
                'category' => 'Makanan', 
                'price' => 38500, 
                'image' => '1 bakul nasi.webp', // Kalau ga ada gambar, hapus baris ini
                'is_available' => true
            ],
            [
                'name' => '1 porsi ca kangkung', 
                'category' => 'Makanan', 
                'price' => 17500, 
                'image' => 'ca kangkung.webp',
                'is_available' => true
            ],
            [
                'name' => 'sambal bawang', 
                'category' => 'Makanan', 
                'price' => 9000, 
                'image' => 'sambal bawang.webp',
                'is_available' => true
            ],
            [
                'name' => 'udang mayonnaise', 
                'category' => 'Makanan', 
                'price' => 55000, 
                'image' => 'udang mayo.webp',
                'is_available' => true
            ],
             [
                'name' => 'Gurami bakar kecap', 
                'category' => 'Makanan', 
                'price' => 80000, 
                'image' => 'gurami bakar kecap.webp',
                'is_available' => true
            ],
             [
                'name' => 'Gurami goreng berdiri', 
                'category' => 'Makanan', 
                'price' => 80000, 
                'image' => 'gurami goreng berdiri.webp',
                'is_available' => true
            ],
             [
                'name' => 'nasi putih per porsi', 
                'category' => 'Makanan', 
                'price' => 7700, 
                'image' => 'nasi putih.webp',
                'is_available' => true
            ],
            [
                'name' => 'nasi tempong ayam', 
                'category' => 'Makanan', 
                'price' => 32000, 
                'image' => 'nasi tempong ayam.webp',
                'is_available' => true
            ],
             [
                'name' => 'nasi tempong empal', 
                'category' => 'Makanan', 
                'price' => 34000, 
                'image' => 'nasi tempong empal.webp',
                'is_available' => true
            ],
            [
                'name' => 'nasi tempong lele', 
                'category' => 'Makanan', 
                'price' => 32000, 
                'image' => 'nasi tempong lele.webp',
                'is_available' => true
            ],
            
            // MINUMAN
            [
                'name' => 'Es Jeruk Nipis', 
                'category' => 'Minuman', 
                'price' => 11000, 
                'image' => 'es jeruk nipis.webp',
                'is_available' => true
            ],
             [
                'name' => 'Lemon Tea', 
                'category' => 'Minuman', 
                'price' => 10000, 
                'image' => 'lemon tea.webp',
                'is_available' => true
            ],
            [
                'name' => 'Es Kelapa Muda', 
                'category' => 'Minuman', 
                'price' => 14000, 
                'image' => 'es kelapa muda.webp',
                'is_available' => true
            ],
            [
                'name' => 'Es Teh Manis', 
                'category' => 'Minuman', 
                'price' => 5000, 
                'image' => 'es teh.webp',
                'is_available' => true
            ],
            
            // PAKET
            [
                'name' => 'Paket Hemat A (Nasi Ayam Bakar+Tempe/tahu+Sayur)', 
                'category' => 'Paket', 
                'price' => 33000, 
                'image' => 'hemat A.webp',
                'is_available' => true
            ],
               [
                'name' => 'Paket Hemat B (Nasi Ayam Goreng+Tempe/tahu)', 
                'category' => 'Paket', 
                'price' => 30000, 
                'image' => 'hemat B.webp',
                'is_available' => true
            ],
            [
                'name' => 'Paket Family Hemat A (5 Pax)', 
                'category' => 'Paket', 
                'price' => 290000, 
                'image' => 'paket family hemat A.webp',
                'is_available' => true
            ],
             [
                'name' => 'Paket Family Hemat B (5 Pax)', 
                'category' => 'Paket', 
                'price' => 310000, 
                'image' => 'paket family hemat b.webp',
                'is_available' => true
            ],
            [
                'name' => 'Paket Family Hemat C (5 Pax)', 
                'category' => 'Paket', 
                'price' => 330000, 
                'image' => 'paket family hemat c.webp',
                'is_available' => true
            ],
        ];

        foreach ($products as $p) {
            $pId = DB::table('products')->insertGetId(array_merge($p, ['created_at' => now(), 'updated_at' => now()]));
            
            // Buat Resep Dummy biar stok jalan (Semua pakai Ayam/Teh)
            if($p['category'] == 'Makanan' || $p['category'] == 'Paket') {
                DB::table('recipes')->insert(['product_id' => $pId, 'ingredient_id' => $ayamId, 'amount_needed' => 1]);
            } else {
                DB::table('recipes')->insert(['product_id' => $pId, 'ingredient_id' => $tehId, 'amount_needed' => 1]);
            }
        }
    }
}