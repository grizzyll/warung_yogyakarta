<template>
    <div class="flex h-[calc(100vh-100px)] gap-4 font-sans p-2"> 
        
        <!-- KIRI: KATALOG PRODUK -->
        <div class="flex-1 flex flex-col h-full overflow-hidden">
            
            <!-- Filter Bar -->
            <div class="flex items-center justify-between mb-4 shrink-0">
                <div class="flex gap-2 overflow-x-auto pb-1">
                    <button v-for="cat in ['Semua', 'Makanan', 'Minuman', 'Paket']" :key="cat"
                            @click="setCategory(cat)" 
                            :class="activeCategory === cat ? 'bg-primary text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200'"
                            class="px-5 py-2 rounded-full font-bold transition text-sm whitespace-nowrap">
                        {{ cat }}
                    </button>
                </div>
            </div>

            <!-- Grid Menu -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 overflow-y-auto pr-2 pb-24 content-start custom-scrollbar">
                
                <div v-for="product in filteredProducts" :key="product.id" 
                     @click="addToCart(product)"
                     class="group bg-white rounded-xl shadow-sm hover:shadow-lg cursor-pointer transition-all border border-gray-200 flex flex-col h-64 overflow-hidden relative">
                    
                    <!-- 1. BAGIAN GAMBAR -->
                    <div class="h-36 bg-gray-200 flex items-center justify-center relative shrink-0 overflow-hidden">
                        <!-- Gambar -->
                        <img v-if="product.image" 
                             :src="'/images/' + product.image" 
                             alt="Menu"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                             @error="$event.target.style.display='none'; $event.target.nextElementSibling.style.display='flex'">
                        
                        <!-- Icon Fallback -->
                        <div :class="product.image ? 'hidden' : 'flex'" class="w-full h-full items-center justify-center z-0">
                            <i v-if="product.category === 'Minuman'" class="fas fa-glass-water text-5xl text-gray-400"></i>
                            <i v-else-if="product.category === 'Paket'" class="fas fa-box-open text-5xl text-gray-400"></i>
                            <i v-else class="fas fa-utensils text-5xl text-gray-400"></i>
                        </div>
                        
                        <!-- Harga -->
                        <div class="absolute top-2 right-2 bg-white/95 backdrop-blur px-2 py-1 rounded text-xs font-bold text-gray-800 shadow-sm z-10">
                            Rp {{ formatPriceShort(product.price) }}
                        </div>
                    </div>

                    <!-- 2. BAGIAN TEKS -->
                    <div class="p-3 bg-white flex flex-col flex-1 relative z-10">
                        <h3 class="font-bold text-gray-800 text-sm leading-snug line-clamp-2 group-hover:text-primary mb-1">
                            {{ product.name }}
                        </h3>
                        
                        <div class="mt-auto flex justify-between items-center">
                            <span class="text-[10px] text-gray-500 uppercase bg-gray-100 px-2 py-1 rounded font-bold">
                                {{ product.category }}
                            </span>
                            <div class="h-6 w-6 rounded-full bg-primary text-white flex items-center justify-center text-xs shadow">
                                <i class="fas fa-plus"></i>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- KANAN: KERANJANG -->
        <div class="w-80 bg-white rounded-xl shadow-lg border border-gray-200 flex flex-col h-full shrink-0">
            <!-- Header -->
            <div class="p-4 border-b border-gray-100 bg-gray-50 rounded-t-xl">
                <h2 class="font-bold text-gray-800 flex justify-between items-center">
                    <span>Pesanan</span>
                    <span class="bg-primary text-white text-xs px-2 py-0.5 rounded-full">{{ cartTotalQty }}</span>
                </h2>
            </div>

            <!-- List Pesanan -->
            <div class="flex-1 overflow-y-auto p-3 space-y-2 custom-scrollbar">
                <div v-if="cart.length === 0" class="h-full flex flex-col items-center justify-center text-gray-400 text-sm">
                    <i class="fas fa-basket-shopping text-2xl mb-2 opacity-50"></i>
                    Keranjang Kosong
                </div>

                <div v-else v-for="(item, index) in cart" :key="index" class="flex gap-2 p-2 bg-white border border-gray-100 rounded-lg shadow-sm">
                    <div class="flex flex-col items-center justify-center gap-1 bg-gray-50 rounded w-6 shrink-0">
                        <button @click="addToCart(item)" class="text-green-600 text-[10px]"><i class="fas fa-plus"></i></button>
                        <span class="font-bold text-xs">{{ item.qty }}</span>
                        <button @click="decreaseQty(index)" class="text-red-500 text-[10px]"><i class="fas fa-minus"></i></button>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-xs font-bold text-gray-800 truncate">{{ item.name }}</div>
                        <div class="flex justify-between text-[10px] text-gray-500 mt-1">
                            <span>@ {{ formatPriceShort(item.price) }}</span>
                            <span class="font-bold text-primary">Rp {{ formatPrice(item.price * item.qty) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- INPUT INFO PELANGGAN (Disini Posisi Barunya) -->
            <div class="px-4 pt-3 bg-white border-t border-gray-100 space-y-2 shrink-0 pb-2">
                <input v-model="customerName" type="text" placeholder="Nama Pelanggan (Opsional)" 
                       class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-xs font-bold focus:ring-2 focus:ring-primary outline-none transition placeholder-gray-400 text-gray-700">
                
                <input v-model="tableNumber" type="text" placeholder="Nomor Meja (Wajib)" 
                       class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-xs font-bold focus:ring-2 focus:ring-primary outline-none transition placeholder-gray-400 text-gray-700">
            </div>

            <!-- Footer Total -->
            <div class="p-4 bg-gray-50 border-t border-gray-100 rounded-b-xl">
                <div class="flex justify-between items-end mb-3">
                    <span class="text-xs text-gray-500 font-bold">TOTAL</span>
                    <span class="text-xl font-black text-gray-800">Rp {{ formatPrice(totalPrice) }}</span>
                </div>
                <button @click="processPayment" :disabled="isLoading || cart.length === 0"
                        class="w-full bg-primary hover:bg-red-800 text-white font-bold py-3 rounded-lg text-sm transition shadow disabled:opacity-50">
                    {{ isLoading ? 'Memproses...' : 'BAYAR' }}
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

// Ref Baru untuk Input
const tableNumber = ref('');
const customerName = ref('');

const filteredProducts = computed(() => {
    return activeCategory.value === 'Semua' 
        ? props.products 
        : props.products.filter(p => p.category === activeCategory.value);
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
    
    // Validasi Nomor Meja
    if (!tableNumber.value) return Swal.fire({ icon: 'warning', title: 'Info Kurang', text: 'Nomor Meja wajib diisi!' });

    const res = await Swal.fire({
        title: 'Bayar?', html: `Total: <b>Rp ${formatPrice(totalPrice.value)}</b><br>Meja: ${tableNumber.value}`,
        icon: 'question', showCancelButton: true, confirmButtonColor: '#B91C1C', confirmButtonText: 'Ya'
    });
    if (!res.isConfirmed) return;

    isLoading.value = true;
    try {
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const resp = await axios.post('/pos/store', { 
            cart: cart.value, 
            total_price: totalPrice.value,
            table_number: tableNumber.value, // Kirim Meja
            customer_name: customerName.value // Kirim Nama
        }, { headers: { 'X-CSRF-TOKEN': token }});
        
        if (resp.data.status === 'success') {
            const nota = resp.data.order_number;
            Swal.fire({ title: 'Sukses', text: 'Nota: ' + nota, icon: 'success', confirmButtonText: '🖨️ Cetak', showCancelButton: true }).then((r) => {
                if(r.isConfirmed) window.open(`/pos/print/${nota}`, '_blank');
            });
            // Reset Semua
            cart.value = [];
            tableNumber.value = '';
            customerName.value = '';
        }
    } catch (e) {
        Swal.fire('Error', 'Gagal', 'error');
    } finally {
        isLoading.value = false;
    }
};
</script>