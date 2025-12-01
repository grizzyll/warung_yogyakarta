@extends('layouts.app')

@section('title', 'Input Belanja Bahan Baku')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="bg-brand-red px-6 py-4 text-white font-bold flex justify-between">
            <span>Form Pembelian (Restock)</span>
            <span class="text-xs bg-red-700 px-2 py-1 rounded">Stok Bertambah & Pengeluaran Tercatat</span>
        </div>

        <form action="{{ route('restock.store') }}" method="POST" class="p-6">
            @csrf

            <!-- Bagian Atas: Supplier & Tanggal -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Supplier</label>
                    <select name="supplier_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-brand-red focus:border-brand-red">
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }} ({{ $supplier->category }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Belanja</label>
                    <input type="date" name="purchase_date" value="{{ date('Y-m-d') }}" class="w-full border-gray-300 rounded-lg shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nomor Nota (Opsional)</label>
                    <input type="text" name="invoice_number" placeholder="Contoh: INV-001" class="w-full border-gray-300 rounded-lg shadow-sm">
                </div>
            </div>

            <!-- Bagian Tabel: Input Bahan -->
            <h3 class="font-bold text-gray-800 mb-2 border-b pb-2">Daftar Bahan Baku</h3>
            <div class="overflow-x-auto mb-6">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 uppercase">
                        <tr>
                            <th class="px-4 py-3">Nama Bahan</th>
                            <th class="px-4 py-3">Sisa Stok</th>
                            <th class="px-4 py-3 w-32">Jml Beli</th>
                            <th class="px-4 py-3 w-40">Harga Satuan (Rp)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($ingredients as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-800">
                                {{ $item->name }} <span class="text-xs text-gray-500">({{ $item->unit }})</span>
                            </td>
                            <td class="px-4 py-3">
                                @if($item->current_stock <= $item->stock_alert)
                                    <span class="text-red-600 font-bold">{{ $item->current_stock }} (Kritis!)</span>
                                @else
                                    <span class="text-green-600 font-bold">{{ $item->current_stock }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <!-- Input Jumlah -->
                                <input type="number" name="items[{{ $item->id }}][qty]" value="0" min="0" 
                                       class="w-full border-gray-300 rounded text-center focus:ring-brand-red focus:border-brand-red">
                            </td>
                            <td class="px-4 py-3">
                                <!-- Input Harga Beli -->
                                <input type="number" name="items[{{ $item->id }}][price]" value="0" min="0" 
                                       class="w-full border-gray-300 rounded text-right focus:ring-brand-red focus:border-brand-red">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end gap-4 border-t pt-4">
                <button type="reset" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-100 font-bold">Reset</button>
                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 font-bold flex items-center">
                    <i class="fas fa-save mr-2"></i> Simpan & Update Stok
                </button>
            </div>
        </form>
    </div>
</div>
@endsection