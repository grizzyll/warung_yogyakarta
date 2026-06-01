<template>
    <!-- WRAPPER UTAMA -->
    <div class="flex flex-col md:flex-row h-[calc(100vh-80px)] md:h-[calc(100vh-100px)] gap-4 md:gap-6 font-sans p-2 md:p-4"> 
        
        <!-- BAGIAN 1: KATALOG PRODUK (KIRI) -->
        <div class="flex-1 flex flex-col h-full overflow-hidden order-1">
            
            <!-- HEADER FILTER & SEARCH -->
            <div class="flex flex-col md:flex-row justify-between gap-3 mb-4 shrink-0">
                
                <!-- 1. SEARCH BAR (Muncul di HP & Laptop) -->
                <!-- Mobile: Order 1 (Paling Atas), Lebar Full -->
                <!-- Desktop: Order 2 (Di Kanan), Lebar 72 -->
                <div class="relative w-full md:w-72 order-1 md:order-2">
                    <input v-model="searchQuery" type="text" placeholder="Cari menu..." 
                           class="w-full pl-10 pr-4 py-2.5 rounded-full border-none shadow-soft focus:ring-2 focus:ring-primary text-sm bg-white transition">
                    <i class="fas fa-search absolute left-4 top-3 text-gray-400"></i>
                    
                    <!-- Tombol Clear (X) -->
                    <button v-if="searchQuery" @click="searchQuery = ''" class="absolute right-3 top-3 text-gray-400 hover:text-red-500">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- 2. TOMBOL KATEGORI -->
                <!-- Mobile: Order 2 (Di Bawah Search) -->
                <!-- Desktop: Order 1 (Di Kiri) -->
                <div class="flex gap-2 overflow-x-auto pb-2 w-full no-scrollbar order-2 md:order-1">
                    <button v-for="cat in ['Semua', 'Makanan', 'Minuman', 'Paket']" :key="cat"
                            @click="setCategory(cat)" 
                            :class="activeCategory === cat ? 'bg-primary text-white shadow-lg transform scale-105' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'"
                            class="px-5 py-2 rounded-full font-bold transition-all duration-200 text-sm whitespace-nowrap flex-shrink-0 shadow-sm">
                        {{ cat }}
                    </button>
                </div>

            </div>

            <!-- Grid Menu -->
            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 overflow-y-auto pr-2 pb-24 content-start custom-scrollbar">
                
                <!-- State Jika Tidak Ada Hasil -->
                <div v-if="filteredProducts.length === 0" class="col-span-full flex flex-col items-center justify-center text-gray-400 py-10 opacity-70">
                    <i class="fas fa-search text-4xl mb-2"></i>
                    <p>Menu tidak ditemukan.</p>
                </div>

                <div v-else v-for="product in filteredProducts" :key="product.id" 
                     @click="addToCart(product)"
                     class="group bg-white rounded-2xl shadow-sm hover:shadow-2xl cursor-pointer transition-all duration-300 border border-gray-100 flex flex-col overflow-hidden relative h-64 md:h-72 transform hover:-translate-y-1">
                    
                    <!-- GAMBAR -->
                    <div class="h-36 md:h-40 bg-gray-100 flex items-center justify-center relative shrink-0 overflow-hidden">
                        <img v-if="product.image" 
                             :src="'/images/' + product.image" 
                             alt="Menu"
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                             @error="$event.target.style.display='none'; $event.target.nextElementSibling.style.display='flex'">
                        
                        <div :class="product.image ? 'hidden' : 'flex'" class="w-full h-full items-center justify-center z-0 bg-gray-50">
                            <i v-if="product.category === 'Minuman'" class="fas fa-glass-water text-5xl text-gray-300"></i>
                            <i v-else-if="product.category === 'Paket'" class="fas fa-box-open text-5xl text-gray-300"></i>
                            <i v-else class="fas fa-utensils text-5xl text-gray-300"></i>
                        </div>
                        
                        <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-md px-3 py-1 rounded-full text-xs font-bold text-gray-900 shadow-sm z-10">
                            Rp {{ formatPriceShort(product.price) }}
                        </div>
                    </div>

                    <!-- TEKS KONTEN -->
                    <div class="p-4 bg-white flex flex-col flex-1 relative z-10 justify-between">
                        <div>
                            <h3 class="font-bold text-gray-800 text-sm md:text-base leading-tight mb-1 line-clamp-2 group-hover:text-primary transition-colors">
                                {{ product.name }}
                            </h3>
                            <p class="text-[10px] md:text-xs text-gray-400 font-medium uppercase tracking-wide">{{ product.category }}</p>
                        </div>
                        <div class="absolute bottom-3 right-3 h-8 w-8 rounded-full bg-primary text-white flex items-center justify-center shadow-lg shadow-red-200 group-hover:scale-110 transition-transform">
                            <i class="fas fa-plus text-xs"></i>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- BAGIAN 2: KERANJANG (KANAN) -->
        <div class="w-full md:w-[380px] bg-white rounded-t-2xl md:rounded-3xl shadow-[0_-5px_30px_rgba(0,0,0,0.05)] md:shadow-2xl border border-gray-100 flex flex-col shrink-0 h-[40vh] md:h-full z-30 order-2 md:order-none">
            
            <!-- Header Keranjang -->
            <div class="p-5 border-b border-gray-100 bg-white md:rounded-t-3xl shrink-0 flex justify-between items-center">
                <h2 class="font-bold text-gray-800 text-lg flex items-center gap-3">
                    <i class="fas fa-receipt text-primary"></i> Current Order
                </h2>
                <span class="bg-red-50 text-primary text-xs font-bold px-3 py-1 rounded-full">{{ cartTotalQty }} Items</span>
            </div>

            <!-- List Pesanan -->
            <div class="flex-1 overflow-y-auto p-4 space-y-3 custom-scrollbar bg-[#FAFAFA]">
                <div v-if="cart.length === 0" class="h-full flex flex-col items-center justify-center text-gray-400 space-y-3 opacity-60">
                    <i class="fas fa-basket-shopping text-4xl mb-2"></i>
                    <p class="font-medium text-sm">Belum ada pesanan</p>
                </div>

                <div v-else v-for="(item, index) in cart" :key="index" class="flex gap-3 p-3 bg-white border border-gray-100 rounded-2xl shadow-sm">
                    <div class="flex flex-col items-center justify-between bg-gray-50 rounded-xl w-8 py-1 shrink-0 h-full border border-gray-100">
                        <button @click="addToCart(item)" class="text-green-600 w-full h-full rounded-t-xl hover:bg-white transition"><i class="fas fa-plus text-[10px]"></i></button>
                        <span class="font-bold text-xs text-gray-800">{{ item.qty }}</span>
                        <button @click="decreaseQty(index)" class="text-red-500 w-full h-full rounded-b-xl hover:bg-white transition"><i class="fas fa-minus text-[10px]"></i></button>
                    </div>
                    
                    <div class="flex-1 min-w-0 flex flex-col justify-center">
                        <div class="text-sm font-bold text-gray-800 leading-tight mb-1 line-clamp-1">{{ item.name }}</div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-400">@ {{ formatPriceShort(item.price) }}</span>
                            <span class="font-bold text-primary text-sm">Rp {{ formatPrice(item.price * item.qty) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Input Nama & Meja -->
            <div class="px-5 pt-4 bg-white border-t border-gray-100 shrink-0 space-y-3 pb-2">
                <div class="flex gap-3">
                    <div class="relative w-1/3">
                        <input v-model="tableNumber" type="text" placeholder="Meja" 
                               class="w-full bg-gray-50 border-gray-200 rounded-xl px-3 py-2 text-sm font-bold focus:ring-2 focus:ring-primary outline-none transition text-center">
                    </div>
                    <div class="relative w-2/3">
                        <input v-model="customerName" type="text" placeholder="Nama Pelanggan" 
                               class="w-full bg-gray-50 border-gray-200 rounded-xl px-3 py-2 text-sm font-bold focus:ring-2 focus:ring-primary outline-none transition">
                    </div>
                </div>
            </div>

            <!-- Footer Total -->
            <div class="p-5 bg-white md:rounded-b-3xl shrink-0">
                <button 
                    @click="processPayment" 
                    :disabled="isLoading || cart.length === 0"
                    class="w-full bg-gradient-to-r from-primary to-red-700 text-white font-bold py-4 rounded-xl shadow-lg hover:shadow-red-200 active:scale-95 transition-all disabled:opacity-50 flex items-center justify-between px-6">
                    <span>{{ isLoading ? 'Memproses...' : 'BAYAR SEKARANG' }}</span>
                    <span class="bg-white/20 px-3 py-1 rounded-lg">Rp {{ formatPrice(totalPrice) }}</span>
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';

const props = defineProps({ products: Array });
const activeCategory = ref('Semua');
const cart = ref([]);
const isLoading = ref(false);
const tableNumber = ref('');
const customerName = ref('');
const searchQuery = ref('');

// FILTER GANDA (KATEGORI + SEARCH)
const filteredProducts = computed(() => {
    let result = props.products;

    // Filter by Category
    if (activeCategory.value !== 'Semua') {
        result = result.filter(p => p.category === activeCategory.value);
    }

    // Filter by Search Query
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        result = result.filter(p => p.name.toLowerCase().includes(query));
    }

    return result;
});

const setCategory = (cat) => activeCategory.value = cat;
const formatPrice = (val) => new Intl.NumberFormat('id-ID').format(val);
const formatPriceShort = (val) => (val / 1000).toFixed(0) + 'k';

const addToCart = (product) => {
    const item = cart.value.find(i => i.id === product.id);
    if (item) item.qty++; else cart.value.push({ ...product, qty: 1 });
};

const decreaseQty = (index) => {
    if (cart.value[index].qty > 1) cart.value[index].qty--; else cart.value.splice(index, 1);
};

const totalPrice = computed(() => cart.value.reduce((sum, i) => sum + (i.price * i.qty), 0));
const cartTotalQty = computed(() => cart.value.reduce((sum, i) => sum + i.qty, 0));

const processPayment = async () => {
    if (!cart.value.length) return Swal.fire({ icon: 'error', title: 'Ups!', text: 'Keranjang kosong.' });
    if (!tableNumber.value) return Swal.fire({ icon: 'warning', title: 'Info', text: 'Nomor Meja Wajib Diisi!' });

    const res = await Swal.fire({
        title: 'Konfirmasi Bayar?', 
        html: `<div class="text-left bg-gray-50 p-4 rounded-lg border border-gray-200 text-sm">
                <div class="flex justify-between mb-2"><span>Total:</span> <span class="font-bold text-red-600">Rp ${formatPrice(totalPrice.value)}</span></div>
                <div class="flex justify-between"><span>Meja:</span> <span class="font-bold text-gray-800">${tableNumber.value}</span></div>
               </div>`,
        icon: 'question', 
        showCancelButton: true, 
        confirmButtonColor: '#B91C1C', 
        confirmButtonText: 'Ya, Bayar',
        cancelButtonText: 'Batal'
    });
    
    if (!res.isConfirmed) return;

    isLoading.value = true;
    try {
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const resp = await axios.post('/pos/store', { 
            cart: cart.value, total_price: totalPrice.value, table_number: tableNumber.value, customer_name: customerName.value 
        }, { headers: { 'X-CSRF-TOKEN': token }});
        
        if (resp.data.status === 'success') {
            const nota = resp.data.order_number;
            Swal.fire({ 
                title: 'Transaksi Sukses!', 
                text: 'Nota: ' + nota, 
                icon: 'success', 
                confirmButtonText: '🖨️ Cetak Struk', 
                showCancelButton: true,
                cancelButtonText: 'Tutup',
                confirmButtonColor: '#3085d6',
                customClass: { popup: 'rounded-2xl' }
            }).then((r) => {
                if(r.isConfirmed) window.open(`/pos/print/${nota}`, '_blank');
            });
            cart.value = []; tableNumber.value = ''; customerName.value = '';
        }
    } catch (e) {
        Swal.fire('Error', 'Gagal memproses transaksi.', 'error');
    } finally {
        isLoading.value = false;
    }
};
</script>