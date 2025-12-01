@extends('layouts.app')

@section('title', 'Input Belanja & Stok')

@section('content')
<div class="max-w-5xl mx-auto">
    
    <!-- Header Card -->
    <div class="bg-white rounded-2xl shadow-soft p-6 mb-6 flex justify-between items-center border border-gray-100">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Purchasing & Restock</h2>
            <p class="text-sm text-gray-500">Catat pembelian bahan baku untuk update stok gudang.</p>
        </div>
        <div class="bg-blue-50 text-blue-700 px-4 py-2 rounded-lg text-sm font-bold flex items-center gap-2">
            <i class="fas fa-info-circle"></i>
            <span>Transaksi > Rp 1.000.000 butuh Approval Owner</span>
        </div>
    </div>

    <!-- Form Area -->
    <form id="restockForm" action="{{ route('restock.store') }}" method="POST" class="bg-white rounded-2xl shadow-soft border border-gray-100 overflow-hidden">
        @csrf

        <!-- Bagian Atas: Supplier Info -->
        <div class="p-8 border-b border-gray-100 bg-gray-50/30">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- Kolom Kiri: Pilih Supplier (Desain Baru) -->
                <div class="md:col-span-1">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Pilih Supplier</label>
                    
                    <!-- Wrapper Relative untuk Icon -->
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-store text-gray-400 group-hover:text-primary transition-colors"></i>
                        </div>
                        
                        <select name="supplier_id" id="supplierSelect" onchange="updateWaLink()" 
                                class="appearance-none w-full bg-white border border-gray-300 text-gray-800 text-sm rounded-xl focus:ring-primary focus:border-primary block pl-10 pr-10 py-3 shadow-sm cursor-pointer hover:border-primary transition-all duration-200 outline-none">
                            <option value="" data-phone="">-- Silakan Pilih --</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" data-phone="{{ $supplier->phone }}">
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>

                        <!-- Custom Arrow Icon -->
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                    
                    <!-- Info Kategori & Tombol WA -->
                    <div class="mt-3 flex items-center justify-between min-h-[24px]">
                        <p class="text-xs text-gray-400 italic" id="supplierCategory">Kategori: -</p>
                        
                        <a id="btnWa" href="#" target="_blank" 
                           class="hidden items-center gap-1.5 text-xs font-bold text-green-600 bg-green-50 px-3 py-1.5 rounded-full hover:bg-green-600 hover:text-white transition-all transform hover:scale-105 shadow-sm border border-green-200">
                            <i class="fab fa-whatsapp text-sm"></i>
                            <span>Order via WA</span>
                        </a>
                    </div>
                </div>

                <!-- Tanggal & Nota -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Tanggal Transaksi</label>
                    <input type="date" name="purchase_date" value="{{ date('Y-m-d') }}" class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-primary focus:border-primary p-3 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">No. Nota / Invoice</label>
                    <input type="text" name="invoice_number" placeholder="Otomatis jika kosong" class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-primary focus:border-primary p-3 text-sm">
                </div>
            </div>
        </div>

        <!-- Bagian Tengah: Tabel Input -->
        <div class="p-8">
            <h3 class="font-bold text-gray-800 text-lg mb-4 flex items-center gap-2">
                <i class="fas fa-boxes text-primary"></i> Input Barang
            </h3>
            
            <div class="overflow-hidden border border-gray-200 rounded-xl">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-100 text-gray-600 font-bold uppercase text-xs">
                        <tr>
                            <th class="px-6 py-4">Nama Bahan</th>
                            <th class="px-6 py-4 text-center">Stok Gudang</th>
                            <th class="px-6 py-4 w-32">Qty Beli</th>
                            <th class="px-6 py-4 w-48">Harga Satuan (Rp)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($ingredients as $item)
                        <tr class="hover:bg-gray-50 transition group">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800">{{ $item->name }}</div>
                                <div class="text-xs text-gray-500">{{ $item->unit }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($item->current_stock <= $item->stock_alert)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        {{ $item->current_stock }} (Low)
                                    </span>
                                @else
                                    <span class="text-gray-600 font-bold">{{ $item->current_stock }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <input type="number" name="items[{{ $item->id }}][qty]" placeholder="0" min="0" 
                                       class="w-full border-gray-200 bg-gray-50 rounded-lg text-center focus:ring-primary focus:border-primary focus:bg-white transition font-bold text-gray-800 p-2">
                            </td>
                            <td class="px-6 py-4">
                                <div class="relative">
                                    <span class="absolute left-3 top-2 text-gray-400">Rp</span>
                                    <input type="number" name="items[{{ $item->id }}][price]" placeholder="0" min="0" 
                                           class="w-full pl-8 border-gray-200 bg-gray-50 rounded-lg text-right focus:ring-primary focus:border-primary focus:bg-white transition p-2">
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Footer Actions -->
        <div class="p-6 bg-gray-50 border-t border-gray-100 flex justify-end gap-4">
            <button type="reset" class="px-6 py-3 rounded-xl border border-gray-300 text-gray-600 font-bold hover:bg-gray-100 transition">
                Reset
            </button>
            <!-- Tombol Simpan Pakai JS SweetAlert -->
            <button type="button" onclick="confirmSubmit()" class="px-8 py-3 rounded-xl bg-primary text-white font-bold shadow-lg hover:bg-red-800 hover:shadow-primary/30 transition transform hover:-translate-y-0.5 flex items-center gap-2">
                <i class="fas fa-save"></i> Simpan Transaksi
            </button>
        </div>
    </form>
</div>

<!-- Script SweetAlert & WA -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function updateWaLink() {
        const select = document.getElementById('supplierSelect');
        const btnWa = document.getElementById('btnWa');
        const catDisplay = document.getElementById('supplierCategory');
        
        const option = select.options[select.selectedIndex];
        let phone = option.getAttribute('data-phone');
        
        // Ambil kategori dari text option (Parsing sederhana)
        let text = option.text.trim();
        // Cek kalau text ada tanda kurung (Category)
        let category = text.includes('(') ? text.split('(').pop().replace(')', '') : 'Umum';

        if (phone) {
            if (phone.startsWith('0')) phone = '62' + phone.substring(1);
            btnWa.href = `https://wa.me/${phone}?text=Halo,%20saya%20dari%20Ayam%20Yogya.%20Mau%20pesan%20stok%20bahan%20baku%20hari%20ini%20apakah%20ada?`;
            
            btnWa.classList.remove('hidden');
            btnWa.classList.add('inline-flex');
            
            catDisplay.innerText = "Kategori: " + category;
            catDisplay.classList.remove('text-gray-400');
            catDisplay.classList.add('text-primary');
        } else {
            btnWa.classList.add('hidden');
            btnWa.classList.remove('inline-flex');
            
            catDisplay.innerText = "Kategori: -";
            catDisplay.classList.add('text-gray-400');
            catDisplay.classList.remove('text-primary');
        }
    }

    function confirmSubmit() {
        Swal.fire({
            title: 'Simpan Data Belanja?',
            text: "Pastikan data jumlah dan harga sudah benar.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#B91C1C',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Ya, Simpan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('restockForm').submit();
            }
        });
    }
</script>
@endsection