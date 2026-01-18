@extends('layouts.app')

@section('title', 'Pusat Laporan (Finance)')

@section('content')
<div class="max-w-5xl mx-auto">
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
        
        <!-- CARD 1: LAPORAN PENJUALAN (OMZET) -->
        <div class="bg-white rounded-2xl shadow-card border border-gray-100 overflow-hidden group hover:shadow-lg transition">
            <div class="p-8 flex flex-col items-center text-center">
                <div class="h-20 w-20 bg-green-50 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition">
                    <i class="fas fa-file-invoice-dollar text-4xl text-green-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Laporan Penjualan</h3>
                <p class="text-sm text-gray-500 mb-8">
                    Unduh data semua transaksi customer dalam format Excel. 
                    Data mencakup: No Nota, Menu terjual, dan Total Pendapatan.
                </p>
                
                <a href="{{ route('reports.export.orders') }}" class="w-full py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl shadow-lg hover:shadow-green-600/30 transition flex items-center justify-center gap-2">
                    <i class="fas fa-file-excel"></i> DOWNLOAD EXCEL
                </a>
            </div>
        </div>

        <!-- CARD 2: LAPORAN PENGELUARAN (STOK) -->
        <div class="bg-white rounded-2xl shadow-card border border-gray-100 overflow-hidden group hover:shadow-lg transition">
            <div class="p-8 flex flex-col items-center text-center">
                <div class="h-20 w-20 bg-red-50 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition">
                    <i class="fas fa-cart-flatbed text-4xl text-red-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Laporan Belanja Stok</h3>
                <p class="text-sm text-gray-500 mb-8">
                    Unduh data pembelian bahan baku ke Supplier.
                    Data mencakup: Supplier, Item dibeli, dan Total Pengeluaran.
                </p>
                
                <a href="{{ route('reports.export.restocks') }}" class="w-full py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl shadow-lg hover:shadow-red-600/30 transition flex items-center justify-center gap-2">
                    <i class="fas fa-file-excel"></i> DOWNLOAD EXCEL
                </a>
            </div>
        </div>

    </div>

</div>
@endsection