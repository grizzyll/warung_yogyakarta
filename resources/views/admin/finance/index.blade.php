@extends('layouts.app')

@section('title', 'Laporan Keuangan Detail')

@section('content')
    <!-- Ringkasan Card -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow-soft border border-green-100">
            <p class="text-xs font-bold text-gray-500 uppercase">Total Pemasukan</p>
            <h3 class="text-2xl font-black text-green-600 mt-1">+ Rp {{ number_format($totalIncome, 0, ',', '.') }}</h3>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-soft border border-red-100">
            <p class="text-xs font-bold text-gray-500 uppercase">Total Pengeluaran</p>
            <h3 class="text-2xl font-black text-red-600 mt-1">- Rp {{ number_format($totalExpense, 0, ',', '.') }}</h3>
        </div>
        <div class="bg-gray-900 p-6 rounded-2xl shadow-card text-white">
            <p class="text-xs font-bold text-gray-400 uppercase">Saldo Kas (Net)</p>
            <h3 class="text-2xl font-black text-white mt-1">Rp {{ number_format($balance, 0, ',', '.') }}</h3>
        </div>
    </div>

    <!-- Tabel Jurnal -->
    <div class="bg-white rounded-2xl shadow-soft border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <h3 class="font-bold text-gray-800"><i class="fas fa-list-alt text-primary mr-2"></i> Jurnal Transaksi</h3>
          <div class="flex gap-2">
    <!-- Tombol Export Pemasukan (Hijau) -->
    <a href="{{ route('reports.export.orders') }}" target="_blank" 
       class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-700 rounded-lg text-xs font-bold hover:bg-green-200 transition shadow-sm">
        <i class="fas fa-file-excel mr-1.5"></i> Export Penjualan
    </a>

    <!-- Tombol Export Pengeluaran (Merah) -->
    <a href="{{ route('reports.export.restocks') }}" target="_blank" 
       class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-xs font-bold hover:bg-red-200 transition shadow-sm">
        <i class="fas fa-file-excel mr-1.5"></i> Export Belanja
    </a>
</div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3">Tanggal & Waktu</th>
                        <th class="px-6 py-3">Keterangan</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Nominal (Rp)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($transactions as $trx)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                            {{ $trx['date']->format('d M Y, H:i') }}
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-800">
                            {{ $trx['desc'] }}
                        </td>
                        <td class="px-6 py-4">
                            @if($trx['status'] == 'success' || $trx['status'] == 'approved')
                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-bold">Selesai</span>
                            @elseif($trx['status'] == 'pending')
                                <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs font-bold">Pending Approval</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right font-bold {{ $trx['type'] == 'in' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $trx['type'] == 'in' ? '+' : '-' }} {{ number_format($trx['amount'], 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection