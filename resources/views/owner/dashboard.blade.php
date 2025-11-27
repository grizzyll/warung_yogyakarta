@extends('layouts.app')

@section('title', 'Executive Dashboard')

@section('content')
    <!-- Notifikasi Sukses Approve -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p class="font-bold">Sukses</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <!-- 1. KARTU KEUANGAN (RINGKASAN) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Kartu 1: Omzet Hari Ini -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-brand-red flex justify-between items-center">
            <div>
                <p class="text-gray-500 text-sm font-semibold uppercase">Omzet Hari Ini</p>
                <h3 class="text-3xl font-black text-gray-800 mt-1">Rp {{ number_format($dailyRevenue, 0, ',', '.') }}</h3>
                <p class="text-xs text-green-600 font-bold mt-2"><i class="fas fa-arrow-up"></i> {{ $dailyCount }} Transaksi</p>
            </div>
            <div class="bg-red-50 p-3 rounded-full text-brand-red">
                <i class="fas fa-coins text-2xl"></i>
            </div>
        </div>

        <!-- Kartu 2: Omzet Bulan Ini -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-brand-yellow flex justify-between items-center">
            <div>
                <p class="text-gray-500 text-sm font-semibold uppercase">Omzet Bulan Ini</p>
                <h3 class="text-3xl font-black text-gray-800 mt-1">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</h3>
                <p class="text-xs text-gray-400 mt-2">Update Realtime</p>
            </div>
            <div class="bg-yellow-50 p-3 rounded-full text-brand-yellow">
                <i class="fas fa-calendar-alt text-2xl"></i>
            </div>
        </div>

        <!-- Kartu 3: Estimasi Laba Bersih (Misal 30% dari Omzet - Simulasi) -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500 flex justify-between items-center">
            <div>
                <p class="text-gray-500 text-sm font-semibold uppercase">Est. Laba Bersih (30%)</p>
                <h3 class="text-3xl font-black text-green-600 mt-1">Rp {{ number_format($monthlyRevenue * 0.3, 0, ',', '.') }}</h3>
                <p class="text-xs text-gray-400 mt-2">Berdasarkan margin rata-rata</p>
            </div>
            <div class="bg-green-50 p-3 rounded-full text-green-600">
                <i class="fas fa-wallet text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- 2. APPROVAL PESANAN BESAR (CATERING) -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="bg-gray-800 px-6 py-4 flex justify-between items-center">
                <h3 class="text-white font-bold"><i class="fas fa-bell text-brand-yellow mr-2"></i> Butuh Persetujuan</h3>
                <span class="bg-red-500 text-white text-xs px-2 py-1 rounded">{{ count($pendingApprovals) }} Pending</span>
            </div>
            
            <div class="p-0">
                @if(count($pendingApprovals) > 0)
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-xs uppercase border-b">
                                <th class="px-6 py-3">Nota</th>
                                <th class="px-6 py-3">Detail</th>
                                <th class="px-6 py-3">Total</th>
                                <th class="px-6 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($pendingApprovals as $order)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-bold text-gray-700">
                                    {{ $order->order_number }}<br>
                                    <span class="text-xs font-normal text-blue-500 bg-blue-50 px-2 py-0.5 rounded">{{ $order->order_type }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <ul class="list-disc pl-4">
                                        @foreach($order->orderItems as $item)
                                            <li>{{ $item->product->name }} (x{{ $item->quantity }})</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="px-6 py-4 font-bold text-brand-red">
                                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <form action="{{ route('owner.approve', $order->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white text-xs font-bold py-2 px-4 rounded shadow transition transform hover:scale-105">
                                            <i class="fas fa-check"></i> SETUJUI
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="p-8 text-center text-gray-400">
                        <i class="fas fa-check-circle text-4xl mb-3 text-green-200"></i>
                        <p>Tidak ada pesanan besar yang tertunda.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- 3. STOCK ALERT & KEBUTUHAN BELANJA -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="bg-brand-red px-6 py-4">
                <h3 class="text-white font-bold"><i class="fas fa-exclamation-triangle text-brand-yellow mr-2"></i> Kebutuhan Belanja (Stok Menipis)</h3>
            </div>
            
            <div class="p-0">
                @if(count($lowStocks) > 0)
                    <table class="w-full text-left">
                        <tbody class="divide-y divide-gray-100">
                            @foreach($lowStocks as $item)
                            <tr class="hover:bg-red-50 transition group">
                                <td class="px-6 py-4">
                                    <p class="font-bold text-gray-800">{{ $item->name }}</p>
                                    <p class="text-xs text-gray-500">Supplier: {{ $item->supplier_id ?? 'Umum' }}</p>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-xs text-gray-500 mr-2">Sisa:</span>
                                    <span class="font-black text-red-600 text-lg">{{ $item->current_stock }} {{ $item->unit }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="#" class="text-xs bg-gray-200 hover:bg-gray-300 px-3 py-1 rounded text-gray-700 font-bold">
                                        <i class="fas fa-phone"></i> Hub. Supplier
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="p-8 text-center text-gray-400">
                        <i class="fas fa-box-open text-4xl mb-3 text-gray-200"></i>
                        <p>Semua stok aman terkendali.</p>
                    </div>
                @endif
            </div>
            <!-- Footer Link -->
            <div class="bg-gray-50 p-3 text-center border-t">
                <a href="#" class="text-sm text-brand-red font-bold hover:underline">Lihat Semua Laporan Stok &rarr;</a>
            </div>
        </div>

    </div>
@endsection