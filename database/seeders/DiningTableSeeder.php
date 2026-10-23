<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DiningTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
{
    foreach (range(1, 10) as $n) {
        \App\Models\DiningTable::firstOrCreate(['number' => (string) $n], ['capacity' => 4]);
    }
}
}
