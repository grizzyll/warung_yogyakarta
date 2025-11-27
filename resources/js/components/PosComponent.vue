
<template>
    <div class="flex h-[calc(100vh-140px)] gap-6"> 
        
        <!-- BAGIAN KIRI: KATALOG MENU -->
        <div class="w-2/3 flex flex-col">
            <!-- Filter Kategori (Contoh UX Simpel) -->
            <div class="flex gap-3 mb-4 overflow-x-auto pb-2">
                <button class="px-6 py-2 bg-brand-red text-white rounded-full font-bold shadow-md hover:bg-red-700 transition">Semua</button>
                <button class="px-6 py-2 bg-white text-gray-600 rounded-full font-bold shadow-sm hover:bg-gray-100 transition border border-gray-200">Makanan</button>
                <button class="px-6 py-2 bg-white text-gray-600 rounded-full font-bold shadow-sm hover:bg-gray-100 transition border border-gray-200">Minuman</button>
            </div>

            <!-- Grid Produk -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 overflow-y-auto pr-2 pb-20">
                <div v-for="product in products" :key="product.id" 
                     @click="addToCart(product)"
                     class="group bg-white rounded-xl shadow-sm hover:shadow-xl cursor-pointer transition-all duration-200 relative overflow-hidden border border-transparent hover:border-brand-yellow">
                    
                    <!-- Foto Dummy -->
                    <div class="h-32 bg-gray-100 flex items-center justify-center group-hover:scale-105 transition-transform duration-500">
                        <!-- Jika ada foto nanti: <img :src="product.image" ...> -->
                        <i class="fas fa-utensils text-4xl text-gray-300 group-hover:text-brand-yellow"></i>
                    </div>

                    <!-- Info Produk -->
                    <div class="p-4">
                        <h3 class="font-bold text-gray-800 leading-tight mb-1 group-hover:text-brand-red">{{ product.name }}</h3>
                        <p class="text-brand-red font-black text-lg">Rp {{ formatPrice(product.price) }}</p>
                    </div>

                    <!-- Efek Klik (Overlay) -->
                    <div class="absolute inset-0 bg-brand-yellow opacity-0 group-active:opacity-20 transition-opacity"></div>
                    
                    <!-- Tombol Plus Kecil -->
                    <div class="absolute bottom-3 right-3 bg-gray-100 text-brand-red w-8 h-8 rounded-full flex items-center justify-center shadow-sm group-hover:bg-brand-red group-hover:text-white transition">
                        <i class="fas fa-plus"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- BAGIAN KANAN: KERANJANG BELANJA -->
        <div class="w-1/3 bg-white rounded-2xl shadow-lg border border-gray-200 flex flex-col overflow-hidden">
            <!-- Header Keranjang -->
            <div class="bg-brand-red p-4 text-white shadow-md z-10">
                <div class="flex justify-between items-center">
                    <h2 class="font-bold text-lg"><i class="fas fa-shopping-basket mr-2"></i> Pesanan</h2>
                    <span class="bg-red-800 text-xs px-2 py-1 rounded-lg">{{ cartTotalQty }} Item</span>
                </div>
            </div>

            <!-- List Item -->
            <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50">
                <div v-if="cart.length === 0" class="h-full flex flex-col items-center justify-center text-gray-400 opacity-60">
                    <i class="fas fa-receipt text-6xl mb-4"></i>
                    <p class="font-medium">Belum ada pesanan</p>
                    <p class="text-sm">Klik menu di kiri untuk menambah</p>
                </div>

                <div v-else v-for="(item, index) in cart" :key="index" class="bg-white p-3 rounded-lg shadow-sm border-l-4 border-brand-yellow flex justify-between items-center">
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-800 text-sm">{{ item.name }}</h4>
                        <div class="text-xs text-gray-500 mt-1">@ Rp {{ formatPrice(item.price) }}</div>
                    </div>
                    
                    <!-- Kontrol Jumlah (UX Mudah) -->
                    <div class="flex items-center gap-3 bg-gray-100 rounded-lg px-2 py-1">
                        <button @click="decreaseQty(index)" class="text-gray-500 hover:text-red-500 w-6 h-6 flex items-center justify-center font-bold">-</button>
                        <span class="font-bold text-gray-800 w-4 text-center text-sm">{{ item.qty }}</span>
                        <button @click="addToCart(item)" class="text-brand-red w-6 h-6 flex items-center justify-center font-bold">+</button>
                    </div>

                    <div class="text-right w-16">
                        <p class="font-bold text-brand-red text-sm">{{ formatPrice(item.price * item.qty) }}</p>
                    </div>
                </div>
            </div>

            <!-- Bagian Bawah: Total & Bayar -->
            <div class="p-5 bg-white border-t border-gray-200 shadow-[0_-5px_15px_rgba(0,0,0,0.05)] z-10">
                <div class="flex justify-between mb-2 text-gray-600 text-sm">
                    <span>Subtotal</span>
                    <span>Rp {{ formatPrice(totalPrice) }}</span>
                </div>
                <div class="flex justify-between mb-4">
                    <span class="text-xl font-bold text-gray-800">Total</span>
                    <span class="text-2xl font-black text-brand-red">Rp {{ formatPrice(totalPrice) }}</span>
                </div>

                <button 
    @click="processPayment" 
    :disabled="isLoading || cart.length === 0"
    class="w-full bg-brand-yellow text-brand-black font-bold text-lg py-4 rounded-xl shadow-lg hover:bg-yellow-400 hover:shadow-xl hover:-translate-y-1 transition-all duration-200 flex items-center justify-center gap-2 group disabled:opacity-50 disabled:cursor-not-allowed">
    
    <span v-if="!isLoading">BAYAR SEKARANG</span>
    <span v-else>Memproses...</span>
    
    <i v-if="!isLoading" class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
</button>
            </div>
        </div>

    </div>
</template>

<script setup>
import Swal from 'sweetalert2'; // <--- TAMBAHKAN INI
import { ref, computed } from 'vue';
import axios from 'axios'; // <--- WAJIB ADA

const props = defineProps({
    products: Array
});

const cart = ref([]);
const isLoading = ref(false); // Biar tombol ga dipencet 2x

// ... (Kode formatPrice, addToCart, decreaseQty, totalPrice TETAP SAMA seperti sebelumnya) ...
// ... Copy paste aja fungsi-fungsi helper yang lama di sini ...

const formatPrice = (value) => {
    return (value / 1000).toFixed(0) + 'k';
};

const addToCart = (product) => {
    const existingItem = cart.value.find(item => item.id === product.id);
    if (existingItem) {
        existingItem.qty++;
    } else {
        cart.value.push({ ...product, qty: 1 });
    }
};

const decreaseQty = (index) => {
    if (cart.value[index].qty > 1) {
        cart.value[index].qty--;
    } else {
        cart.value.splice(index, 1);
    }
};

const totalPrice = computed(() => {
    return cart.value.reduce((sum, item) => sum + (item.price * item.qty), 0);
});

const cartTotalQty = computed(() => {
    return cart.value.reduce((sum, item) => sum + item.qty, 0);
});

// --- FUNGSI BAYAR DENGAN POPUP CANTIK ---
const processPayment = async () => {
    // 1. Cek Keranjang Kosong
    if (cart.value.length === 0) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Keranjang masih kosong!',
            confirmButtonColor: '#D9232D',
        });
        return;
    }

    // 2. Konfirmasi Pembayaran (Pengganti confirm biasa)
    const result = await Swal.fire({
        title: 'Konfirmasi Pembayaran',
        html: `Total tagihan: <b>Rp ${formatPrice(totalPrice.value)}</b><br>Proses pesanan ini?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#D9232D', // Warna Merah Brand
        cancelButtonColor: '#9CA3AF',
        confirmButtonText: 'Ya, Bayar!',
        cancelButtonText: 'Batal'
    });

    // Kalau user klik Batal, berhenti di sini
    if (!result.isConfirmed) return;

    isLoading.value = true;

    try {
        const tokenMeta = document.querySelector('meta[name="csrf-token"]');
        const token = tokenMeta ? tokenMeta.getAttribute('content') : '';

        const response = await axios.post('/pos/store', {
            cart: cart.value,
            total_price: totalPrice.value
        }, {
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json'
            }
        });

        if (response.data.status === 'success') {
            // 3. Pesan Sukses Cantik
            Swal.fire({
                title: 'Transaksi Berhasil!',
                text: `Nomor Nota: ${response.data.order_number}`,
                icon: 'success',
                confirmButtonColor: '#D9232D',
                timer: 3000, // Otomatis tutup dalam 3 detik
                timerProgressBar: true
            });

            cart.value = []; // Kosongkan keranjang
        }

    } catch (error) {
        console.error(error);
        // 4. Pesan Error Cantik
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Terjadi kesalahan pada sistem.',
            confirmButtonColor: '#D9232D'
        });
    } finally {
        isLoading.value = false;
    }
};
</script>