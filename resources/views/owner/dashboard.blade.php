@extends('layouts.app')

@section('title', 'Financial Overview')

@section('content')
    <!-- 1. RINGKASAN KEUANGAN (Cards) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Pemasukan -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border-l-4 border-green-500 relative overflow-hidden group">
            <div class="absolute right-0 top-0 p-4 opacity-10"><i class="fas fa-arrow-trend-up text-6xl text-green-600"></i>
            </div>
            <p class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">Pemasukan (Omzet)</p>
            <h3 class="text-2xl font-black text-gray-800">Rp {{ number_format($incomeTotal, 0, ',', '.') }}</h3>
            <p class="text-xs text-gray-400 mt-2">
                {{ $filter == 'year' ? 'Tahun Ini' : ($filter == 'month' ? 'Bulan Ini' : 'Minggu Ini') }}
            </p>
        </div>

        <!-- Pengeluaran -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border-l-4 border-red-500 relative overflow-hidden group">
            <div class="absolute right-0 top-0 p-4 opacity-10"><i class="fas fa-arrow-trend-down text-6xl text-red-600"></i>
            </div>
            <p class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">Pengeluaran (Stok)</p>
            <h3 class="text-2xl font-black text-gray-800">Rp {{ number_format($expenseTotal, 0, ',', '.') }}</h3>
            <p class="text-xs text-gray-400 mt-2">
                {{ $filter == 'year' ? 'Tahun Ini' : ($filter == 'month' ? 'Bulan Ini' : 'Minggu Ini') }}
            </p>
        </div>

        <!-- Profit Bersih -->
        <div class="bg-gray-900 p-5 rounded-2xl shadow-lg relative overflow-hidden group text-white">
            <div class="absolute right-0 top-0 p-4 opacity-20"><i class="fas fa-wallet text-6xl text-blue-400"></i></div>
            <p class="text-gray-400 text-xs font-bold uppercase tracking-wider mb-1">Keuntungan Bersih</p>
            <h3 class="text-3xl font-black {{ $netProfit >= 0 ? 'text-blue-400' : 'text-red-400' }}">
                Rp {{ number_format($netProfit, 0, ',', '.') }}
            </h3>
            <p class="text-xs text-gray-500 mt-2">Pemasukan - Pengeluaran</p>
        </div>
    </div>

 <!-- 2. EXECUTIVE CHART -->
    <div class="bg-white p-6 rounded-2xl shadow-card border border-gray-100 mb-8">
        
        <!-- HEADER CHART -->
        <div class="flex flex-col xl:flex-row justify-between items-start md:items-center mb-6 gap-4">
            
            <!-- A. JUDUL & MODE SWITCHER -->
            <div class="flex flex-col md:flex-row items-center gap-4">
                <div>
                    <h3 class="font-bold text-gray-800 text-lg">Financial Analytics</h3>
                    <!-- LEGEND BARU (BADGE STYLE - LEBIH CLEAN) -->
                    <div class="flex gap-2 mt-1">
                        @if($chartMode == 'profit')
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">
                                Pemasukan
                            </span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-50 text-red-600 border border-red-100">
                                Pengeluaran
                            </span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-600 border border-blue-100">
                                Profit Bersih
                            </span>
                        @else
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-50 text-red-600 border border-red-100">
                                Periode Ini
                            </span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-500 border border-gray-200">
                                Periode Lalu
                            </span>
                        @endif
                    </div>
                </div>
                
                <!-- MODE SWITCHER -->
                <div class="bg-gray-100 p-1 rounded-lg flex">
                    <a href="{{ route('dashboard', ['mode' => 'profit', 'filter' => $filter, 'year' => $selectedYear]) }}" 
                       class="px-4 py-1.5 rounded-md text-xs font-bold transition flex items-center gap-2 {{ $chartMode == 'profit' ? 'bg-white text-primary shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                       <i class="fas fa-wallet"></i> Profit
                    </a>
                    <a href="{{ route('dashboard', ['mode' => 'growth', 'filter' => $filter, 'year' => $selectedYear]) }}" 
                       class="px-4 py-1.5 rounded-md text-xs font-bold transition flex items-center gap-2 {{ $chartMode == 'growth' ? 'bg-white text-primary shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                       <i class="fas fa-chart-line"></i> Growth
                    </a>
                </div>
            </div>

            <!-- B. FILTER WAKTU (Kanan) -->
            <div class="flex items-center gap-2">
                @if($filter == 'year' || $filter == 'month')
                <form id="yearForm" action="{{ route('dashboard') }}" method="GET">
                    <input type="hidden" name="mode" value="{{ $chartMode }}">
                    <input type="hidden" name="filter" value="{{ $filter }}">
                    <select name="year" onchange="document.getElementById('yearForm').submit()" class="bg-gray-50 border-gray-200 text-xs rounded-lg font-bold p-2 cursor-pointer focus:ring-0">
                        @for($i = 2024; $i <= date('Y') + 1; $i++)
                            <option value="{{ $i }}" {{ $selectedYear == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </form>
                @endif

                <div class="flex bg-gray-100 p-1 rounded-lg">
                    @foreach(['week'=>'7 Hari', 'month'=>'Bulanan', 'year'=>'Tahunan'] as $key => $label)
                    <a href="{{ route('dashboard', ['filter' => $key, 'mode' => $chartMode, 'year' => $selectedYear]) }}" 
                       class="px-3 py-1.5 rounded-md text-xs font-bold transition {{ $filter == $key ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                       {{ $label }}
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
        
        <!-- CANVAS -->
        <div class="relative h-80 w-full">
            <canvas id="financeChart"></canvas>
        </div>
    </div>
    <!-- 3. AREA APPROVAL & ALERTS -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        <!-- Left: Needs Attention -->
        <div class="space-y-6">
            <!-- CATERING APPROVAL -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-3 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                    <h4 class="font-bold text-gray-700 text-sm">Approval Catering</h4>
                    @if(count($pendingApprovals) > 0)
                        <span
                            class="bg-red-100 text-red-600 text-[10px] font-bold px-2 py-1 rounded-full animate-pulse">{{ count($pendingApprovals) }}
                            New</span>
                    @endif
                </div>
                <div class="p-0">
                    @forelse($pendingApprovals as $order)
                        <div class="p-4 border-b border-gray-50 flex justify-between items-center">
                            <div>
                                <div class="font-bold text-sm">{{ $order->order_number }}</div>
                                <div class="text-xs text-gray-500">Rp {{ number_format($order->total_price) }}</div>
                            </div>
                            <form action="{{ route('owner.approve', $order->id) }}" method="POST">
                                @csrf
                                <button
                                    class="text-xs bg-green-100 text-green-700 px-3 py-1.5 rounded-lg font-bold hover:bg-green-200">Setujui</button>
                            </form>
                        </div>
                    @empty
                        <div class="p-6 text-center text-xs text-gray-400">Aman, tidak ada antrian.</div>
                    @endforelse
                </div>
            </div>

            <!-- RESTOCK APPROVAL -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-3 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                    <h4 class="font-bold text-gray-700 text-sm">Approval Belanja Stok</h4>
                    @if(count($pendingRestocks) > 0)
                        <span
                            class="bg-blue-100 text-blue-600 text-[10px] font-bold px-2 py-1 rounded-full">{{ count($pendingRestocks) }}
                            New</span>
                    @endif
                </div>
                <div class="p-0">
                    @forelse($pendingRestocks as $restock)
                        <div class="p-4 border-b border-gray-50 flex justify-between items-center">
                            <div>
                                <div class="font-bold text-sm">{{ $restock->supplier->name }}</div>
                                <div class="text-xs text-gray-500">Rp {{ number_format($restock->total_spent) }}</div>
                            </div>
                            <form action="{{ route('owner.approveRestock', $restock->id) }}" method="POST">
                                @csrf
                                <button
                                    class="text-xs bg-blue-100 text-blue-700 px-3 py-1.5 rounded-lg font-bold hover:bg-blue-200">ACC</button>
                            </form>
                        </div>
                    @empty
                        <div class="p-6 text-center text-xs text-gray-400">Tidak ada pengajuan belanja.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right: Stock Alert -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden h-fit">
            <div class="px-5 py-3 bg-red-50 border-b border-red-100 text-red-800 flex justify-between items-center">
                <h4 class="font-bold text-sm"><i class="fas fa-triangle-exclamation mr-2"></i> Stok Menipis</h4>
            </div>
            <div class="p-0">
                @forelse($lowStocks as $item)
                    <div class="p-3 border-b border-gray-50 flex justify-between items-center hover:bg-gray-50">
                        <div class="text-sm text-gray-700">{{ $item->name }}</div>
                        <div class="text-sm font-bold text-red-600">{{ $item->current_stock }} {{ $item->unit }}</div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-400">
                        <i class="fas fa-check-circle text-3xl mb-2 text-green-200"></i>
                        <p class="text-xs">Gudang Aman.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('financeChart').getContext('2d');
        
        const mode = "{!! $chartMode !!}";
        const labels = {!! json_encode($labels) !!};
        
        let datasets = [];

        // SETUP DATASETS
        if (mode === 'profit') {
            datasets = [
                {
                    label: 'Pemasukan',
                    data: {!! json_encode($incomeData) !!},
                    borderColor: '#10B981', // Emerald
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 2, 
                    tension: 0.4, 
                    fill: true,
                    pointRadius: 0, // CLEAN LOOK: Hilangkan titik
                    pointHoverRadius: 6 // Titik muncul cuma pas di-hover
                },
                {
                    label: 'Pengeluaran',
                    data: {!! json_encode($expenseData) !!},
                    borderColor: '#EF4444', // Red
                    backgroundColor: 'rgba(239, 68, 68, 0.05)',
                    borderWidth: 2, 
                    borderDash: [5,5], 
                    tension: 0.4, 
                    fill: true,
                    pointRadius: 0,
                    pointHoverRadius: 6
                },
                {
                    label: 'Profit Bersih',
                    data: {!! json_encode($profitData) !!},
                    borderColor: '#3B82F6', // Blue
                    borderWidth: 3, 
                    tension: 0.4, 
                    fill: false,
                    pointRadius: 0,
                    pointHoverRadius: 6
                }
            ];
        } else {
            // MODE GROWTH
            datasets = [
                {
                    label: 'Periode Ini',
                    data: {!! json_encode($currentData) !!},
                    borderColor: '#D9232D', 
                    backgroundColor: 'rgba(217, 35, 45, 0.1)',
                    borderWidth: 3, 
                    tension: 0.4, 
                    fill: true,
                    pointRadius: 0,
                    pointHoverRadius: 6
                },
                {
                    label: 'Periode Lalu',
                    data: {!! json_encode($previousData) !!},
                    borderColor: '#9CA3AF', 
                    borderWidth: 2, 
                    borderDash: [5,5], 
                    tension: 0.4, 
                    fill: false,
                    pointRadius: 0,
                    pointHoverRadius: 6
                }
            ];
        }

        new Chart(ctx, {
            type: 'line',
            data: { labels: labels, datasets: datasets },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false }, // HILANGKAN LEGEND BAWAAN (Jelek)
                    tooltip: {
                        backgroundColor: 'rgba(255, 255, 255, 0.9)',
                        titleColor: '#1F2937',
                        bodyColor: '#1F2937',
                        borderColor: '#E5E7EB',
                        borderWidth: 1,
                        padding: 10, 
                        cornerRadius: 8,
                        titleFont: { size: 13, weight: 'bold' },
                        callbacks: {
                            label: function(ctx) {
                                return ctx.dataset.label + ': Rp ' + new Intl.NumberFormat('id-ID').format(ctx.raw);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [4, 4], color: '#F3F4F6' },
                        ticks: { 
                            callback: (val) => (val/1000)+'k', 
                            font: {size: 10}, 
                            color: '#9CA3AF',
                            maxTicksLimit: 6 // Batasi jumlah garis horizontal
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { 
                            font: {size: 10}, 
                            color: '#9CA3AF',
                            maxTicksLimit: 8, // <--- INI KUNCINYA (Biar sumbu X ga penuh sesak)
                            maxRotation: 0 // Biar tulisan ga miring-miring
                        }
                    }
                }
            }
        });
    });
</script>
@endsection