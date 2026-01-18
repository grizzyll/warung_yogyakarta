<template>
    <div class="p-6 bg-gray-100 min-h-screen font-sans">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-black text-gray-800 flex items-center gap-3">
                <i class="fas fa-fire-burner text-red-600 animate-pulse"></i> 
                Kitchen Display System
            </h2>
            <div class="flex items-center gap-2 text-sm font-bold bg-white px-4 py-2 rounded-lg shadow-sm text-gray-500">
                <span class="w-3 h-3 rounded-full bg-green-500 animate-ping"></span>
                Live Update (5s)
            </div>
        </div>

        <!-- Tampilan Jika Kosong -->
        <div v-if="orders.length === 0" class="flex flex-col items-center justify-center py-32 text-gray-400 opacity-60">
            <i class="fas fa-utensils text-8xl mb-4"></i>
            <h3 class="text-2xl font-bold">Dapur Bersih & Tenang</h3>
            <p>Belum ada pesanan masuk.</p>
        </div>

        <!-- Grid Kartu Pesanan -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <div v-for="order in orders" :key="order.id" 
                class="bg-white rounded-2xl shadow-lg overflow-hidden border-t-8 transition-all duration-300 transform hover:-translate-y-1"
                :class="order.status === 'pending' ? 'border-red-500' : 'border-yellow-400'">
                
                <!-- HEADER KARTU -->
                <div class="p-4 border-b border-gray-100 flex justify-between items-start bg-gray-50">
                    <div>
                        <!-- Nomor Order Besar -->
                        <h3 class="font-black text-2xl text-gray-800">#{{ order.order_number.split('-').pop() }}</h3>
                        <!-- Nama Pelanggan -->
                        <p class="text-xs font-bold text-gray-500 uppercase mt-1">
                            <i class="fas fa-user mr-1"></i> {{ order.customer_name || 'Pelanggan' }}
                        </p>
                    </div>
                    
                    <div class="text-right">
                        <!-- JAM -->
                        <p class="text-xs font-bold text-gray-400">{{ formatTime(order.created_at) }}</p>
                        <!-- NOMOR MEJA (PENTING!) -->
                        <div class="mt-1 bg-blue-100 text-blue-800 text-sm font-black px-2 py-1 rounded border border-blue-200">
                            {{ order.table_number || 'TAKEAWAY' }}
                        </div>
                    </div>
                </div>

                <!-- ISI PESANAN -->
                <div class="p-4 space-y-3 min-h-[120px]">
                    <div v-for="item in order.order_items" :key="item.id" class="flex items-start gap-3 border-b border-dashed border-gray-100 pb-2 last:border-0">
                        <span class="font-black text-lg w-8 h-8 flex shrink-0 items-center justify-center bg-gray-800 text-white rounded-lg shadow-sm">
                            {{ item.quantity }}
                        </span>
                        <span class="font-bold text-gray-700 leading-tight py-1">
                            {{ item.product.name }}
                        </span>
                    </div>
                </div>

                <!-- FOOTER TOMBOL -->
                <div class="p-3 bg-gray-100 grid grid-cols-1 gap-2">
                    <!-- Tombol TERIMA (Jika masih Pending) -->
                    <button v-if="order.status === 'pending'" 
                            @click="updateStatus(order.id, 'cooking')"
                            class="w-full py-3 bg-yellow-400 text-yellow-900 font-black rounded-xl shadow-sm hover:bg-yellow-300 transition flex items-center justify-center gap-2 active:scale-95">
                        <i class="fas fa-fire"></i> MASAK SEKARANG
                    </button>

                    <!-- Tombol SELESAI (Jika sedang Cooking) -->
                    <button v-if="order.status === 'cooking'" 
                            @click="updateStatus(order.id, 'ready')"
                            class="w-full py-3 bg-green-500 text-white font-black rounded-xl shadow-sm hover:bg-green-600 transition flex items-center justify-center gap-2 active:scale-95">
                        <i class="fas fa-check-double"></i> SIAP SAJI
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';

const orders = ref([]);
const loading = ref(false);
let timer = null;

const formatTime = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
};

const fetchOrders = async () => {
    try {
        const response = await axios.get('/kitchen/api/orders');
        orders.value = response.data;
    } catch (error) {
        console.error("Gagal mengambil data order", error);
    }
};

const updateStatus = async (orderId, newStatus) => {
    try {
        await axios.post(`/kitchen/api/update/${orderId}`, { status: newStatus });
        fetchOrders(); // Refresh instan
        
        // Sound Effect (Opsional: Ting!)
        // const audio = new Audio('/sound/ding.mp3'); audio.play();

    } catch (error) {
        Swal.fire('Error', 'Gagal update status', 'error');
    }
};

onMounted(() => {
    fetchOrders();
    timer = setInterval(fetchOrders, 5000); // Cek order baru tiap 5 detik
});

onUnmounted(() => {
    if (timer) clearInterval(timer);
});
</script>