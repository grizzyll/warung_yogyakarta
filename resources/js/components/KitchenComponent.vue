<template>
    <div class="p-4">
        <h2 class="text-2xl font-bold mb-6 text-gray-800 flex items-center">
            <i class="fas fa-fire text-brand-red mr-3"></i> Antrian Dapur
            <span v-if="loading" class="text-sm ml-4 text-gray-500 animate-pulse">Memuat data...</span>
        </h2>

        <!-- Tampilan Jika Kosong -->
        <div v-if="orders.length === 0" class="text-center py-20 bg-white rounded-xl shadow-sm border border-gray-200">
            <i class="fas fa-mug-hot text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-bold text-gray-500">Tidak ada pesanan aktif</h3>
            <p class="text-gray-400">Silakan santai sejenak...</p>
        </div>

        <!-- Grid Kartu Pesanan -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <div v-for="order in orders" :key="order.id" 
                class="bg-white rounded-xl shadow-md overflow-hidden border-t-8 transition-all duration-300"
                :class="order.status === 'pending' ? 'border-brand-red' : 'border-brand-yellow'">
                
                <!-- Header Kartu -->
                <div class="p-4 border-b flex justify-between items-start" 
                     :class="order.status === 'pending' ? 'bg-red-50' : 'bg-yellow-50'">
                    <div>
                        <h3 class="font-black text-xl text-gray-800">#{{ order.order_number.split('-').pop() }}</h3>
                        <p class="text-xs text-gray-500">{{ formatTime(order.created_at) }}</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider"
                          :class="order.status === 'pending' ? 'bg-red-200 text-red-800' : 'bg-yellow-200 text-yellow-800'">
                        {{ order.status }}
                    </span>
                </div>

                <!-- Isi Pesanan -->
                <div class="p-4 space-y-3 min-h-[150px]">
                    <div v-for="item in order.order_items" :key="item.id" class="flex justify-between items-center border-b border-dashed pb-2 last:border-0">
                        <div class="flex items-center">
                            <span class="font-bold text-lg w-8 h-8 flex items-center justify-center bg-gray-100 rounded-md mr-3 text-brand-red">
                                {{ item.quantity }}
                            </span>
                            <span class="font-medium text-gray-700 leading-tight">
                                {{ item.product.name }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Footer Tombol Aksi -->
                <div class="p-4 bg-gray-50 border-t">
                    <!-- Tombol TERIMA (Jika masih Pending) -->
                    <button v-if="order.status === 'pending'" 
                            @click="updateStatus(order.id, 'cooking')"
                            class="w-full py-3 bg-brand-yellow text-brand-black font-bold rounded-lg shadow hover:bg-yellow-400 transition flex items-center justify-center gap-2">
                        <i class="fas fa-fire-burner"></i> MASAK
                    </button>

                    <!-- Tombol SELESAI (Jika sedang Cooking) -->
                    <button v-if="order.status === 'cooking'" 
                            @click="updateStatus(order.id, 'ready')"
                            class="w-full py-3 bg-green-600 text-white font-bold rounded-lg shadow hover:bg-green-700 transition flex items-center justify-center gap-2">
                        <i class="fas fa-check-circle"></i> SIAP SAJI
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

// Format Jam (Contoh: 14:30)
const formatTime = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
};

// Ambil Data dari API
const fetchOrders = async () => {
    try {
        loading.value = true;
        const response = await axios.get('/kitchen/api/orders');
        orders.value = response.data;
    } catch (error) {
        console.error("Gagal mengambil data order", error);
    } finally {
        loading.value = false;
    }
};

// Update Status Order
const updateStatus = async (orderId, newStatus) => {
    try {
        await axios.post(`/kitchen/api/update/${orderId}`, { status: newStatus });
        
        // Notifikasi kecil
        const Toast = Swal.mixin({
            toast: true, position: 'top-end', showConfirmButton: false, timer: 2000
        });
        Toast.fire({ icon: 'success', title: newStatus === 'cooking' ? '👨‍🍳 Mulai Memasak' : '✅ Pesanan Selesai' });

        // Refresh data langsung
        fetchOrders();

    } catch (error) {
        Swal.fire('Error', 'Gagal update status', 'error');
    }
};

// Saat komponen dipasang (Halaman dibuka)
onMounted(() => {
    fetchOrders(); // Ambil data pertama kali
    
    // Auto Refresh setiap 5 Detik (Realtime sederhana)
    timer = setInterval(fetchOrders, 5000);
});

// Saat keluar halaman, matikan timer biar gak berat
onUnmounted(() => {
    if (timer) clearInterval(timer);
});
</script>