@extends('layouts.app')

@section('title', 'Executive Overview')

@section('content')
    <!-- STATS CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Revenue Card -->
        <div class="bg-white p-6 rounded-2xl shadow-soft border border-gray-100 relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition">
                <i class="fas fa-coins text-8xl text-primary"></i>
            </div>
            <p class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">Omzet Hari Ini</p>
            <h3 class="text-3xl font-black text-gray-800">Rp {{ number_format($dailyRevenue, 0, ',', '.') }}</h3>
            <div class="mt-4 flex items-center text-xs font-medium text-green-600 bg-green-50 w-fit px-2 py-1 rounded-lg">
                <i class="fas fa-arrow-up mr-1"></i> {{ $dailyCount }} Transaksi
            </div>
        </div>

        <!-- Monthly Card -->
        <div class="bg-white p-6 rounded-2xl shadow-soft border border-gray-100 relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition">
                <i class="fas fa-calendar-check text-8xl text-accent"></i>
            </div>
            <p class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">Omzet Bulan Ini</p>
            <h3 class="text-3xl font-black text-gray-800">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</h3>
            <div class="mt-4 text-xs text-gray-400">Update Realtime</div>
        </div>

        <!-- Profit Card -->
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 p-6 rounded-2xl shadow-card text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <i class="fas fa-wallet text-8xl text-white"></i>
            </div>
            <p class="text-gray-400 text-xs font-bold uppercase tracking-wider mb-1">Estimasi Laba Bersih</p>
            <h3 class="text-3xl font-black text-green-400">Rp {{ number_format($monthlyRevenue * 0.3, 0, ',', '.') }}</h3>
            <p class="text-xs text-gray-400 mt-4 opacity-70">Berdasarkan margin 30%</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- LEFT: APPROVALS (Span 2) -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- CATERING APPROVAL -->
            <div class="bg-white rounded-2xl shadow-soft border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <span class="w-2 h-6 bg-accent rounded-full"></span> Approval Catering
                    </h3>
                    @if(count($pendingApprovals) > 0)
                        <span class="bg-red-100 text-red-600 text-xs font-bold px-2 py-1 rounded-lg animate-pulse">{{ count($pendingApprovals) }} Pending</span>
                    @endif
                </div>
                
                <div class="p-0">
                    @forelse($pendingApprovals as $order)
                    <div class="p-4 border-b border-gray-50 last:border-0 hover:bg-gray-50 transition flex justify-between items-center">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-bold text-gray-800">{{ $order->order_number }}</span>
                                <span class="text-[10px] bg-blue-100 text-blue-700 px-1.5 py-0.5 rounded uppercase font-bold">{{ $order->order_type }}</span>
                            </div>
                            <p class="text-sm text-gray-500">
                                @foreach($order->orderItems as $item) {{ $item->product->name }} (x{{ $item->quantity }}), @endforeach
                            </p>
                        </div>
                        <div class="text-right flex items-center gap-4">
                            <span class="font-bold text-primary text-lg">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                            <form action="{{ route('owner.approve', $order->id) }}" method="POST">
                                @csrf
                                <button class="bg-green-100 text-green-700 hover:bg-green-600 hover:text-white px-4 py-2 rounded-lg text-xs font-bold transition">
                                    Setujui
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="p-8 text-center text-gray-400">
                        <i class="fas fa-check-circle text-4xl mb-2 text-gray-200"></i>
                        <p class="text-sm">Semua pesanan aman.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- RESTOCK APPROVAL -->
            <div class="bg-white rounded-2xl shadow-soft border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <span class="w-2 h-6 bg-blue-500 rounded-full"></span> Approval Belanja (> 1 Juta)
                    </h3>
                    @if(count($pendingRestocks) > 0)
                        <span class="bg-blue-100 text-blue-600 text-xs font-bold px-2 py-1 rounded-lg">{{ count($pendingRestocks) }} Pending</span>
                    @endif
                </div>
                <div class="p-0">
                    @forelse($pendingRestocks as $restock)
                    <div class="p-4 border-b border-gray-50 last:border-0 hover:bg-gray-50 transition flex justify-between items-center">
                        <div>
                            <div class="font-bold text-gray-800 mb-1">{{ $restock->supplier->name }}</div>
                            <div class="text-xs text-gray-500">
                                @foreach($restock->items as $item) {{ $item->ingredient->name }}: <b>{{ $item->quantity }} {{ $item->ingredient->unit }}</b>, @endforeach
                            </div>
                        </div>
                        <div class="text-right flex items-center gap-4">
                            <span class="font-bold text-gray-800 text-lg">Rp {{ number_format($restock->total_spent, 0, ',', '.') }}</span>
                            <form action="{{ route('owner.approveRestock', $restock->id) }}" method="POST">
                                @csrf
                                <button class="bg-blue-100 text-blue-700 hover:bg-blue-600 hover:text-white px-4 py-2 rounded-lg text-xs font-bold transition">
                                    ACC Belanja
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="p-8 text-center text-gray-400">
                        <p class="text-sm">Tidak ada belanja pending.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- RIGHT: ALERTS -->
        <div class="bg-white rounded-2xl shadow-soft border border-gray-100 overflow-hidden h-fit">
            <div class="bg-primary px-6 py-4 text-white">
                <h3 class="font-bold flex items-center gap-2">
                    <i class="fas fa-exclamation-triangle text-accent"></i> Stok Menipis
                </h3>
            </div>
            <div class="p-0">
                @forelse($lowStocks as $item)
                <div class="p-4 border-b border-gray-50 flex justify-between items-center hover:bg-red-50 transition">
                    <div>
                        <div class="font-bold text-gray-800 text-sm">{{ $item->name }}</div>
                        <div class="text-xs text-gray-500">Alert: {{ $item->stock_alert }} {{ $item->unit }}</div>
                    </div>
                    <div class="text-right">
                        <div class="text-lg font-black text-primary">{{ $item->current_stock }}</div>
                        <div class="text-[10px] text-gray-400">Sisa Stok</div>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-gray-400">
                    <i class="fas fa-box text-4xl mb-2 text-gray-200"></i>
                    <p class="text-sm">Gudang Aman.</p>
                </div>
                @endforelse
            </div>
            <div class="bg-gray-50 p-3 text-center text-xs text-gray-400">
                Hubungi Admin untuk restock
            </div>
        </div>
    </div>
@endsection