@extends('layouts.app')

@section('title', 'Dashboard Utama')

@section('content')
    <!-- Statistik Atas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <!-- Kotak 1 -->
        <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-brand-red">
            <p class="text-gray-500 text-sm font-medium">Order Hari Ini</p>
            <h3 class="text-3xl font-bold text-gray-800 mt-2">125</h3>
        </div>

        <!-- Kotak 2 -->
        <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-brand-yellow">
            <p class="text-gray-500 text-sm font-medium">Pendapatan</p>
            <h3 class="text-3xl font-bold text-gray-800 mt-2">Rp 3.5jt</h3>
        </div>

        <!-- Kotak 3: Stok -->
        <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-blue-500">
            <p class="text-gray-500 text-sm font-medium">Stok Ayam</p>
            <h3 class="text-3xl font-bold text-gray-800 mt-2">45 Ekor</h3>
            <span class="text-red-500 text-xs font-bold">Low Stock!</span>
        </div>

        <!-- Kotak 4 -->
        <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-green-500">
            <p class="text-gray-500 text-sm font-medium">Meja Terisi</p>
            <h3 class="text-3xl font-bold text-gray-800 mt-2">8 / 12</h3>
        </div>
    </div>

    <!-- Contoh Vue Component tadi (Opsional) -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b">
            Test Vue Component
        </h3>
        <hello-vue></hello-vue>
    </div>
@endsection