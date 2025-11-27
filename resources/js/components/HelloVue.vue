<template>
    <div class="p-6 bg-white rounded-lg shadow-md border-l-4 border-brand-red">
        <h2 class="text-2xl font-bold text-brand-red">Sistem Ayam Yogya</h2>
        <p class="text-brand-yellow font-semibold">Vue.js Berhasil Diinstall!</p>
        <button @click="count++" class="mt-4 px-4 py-2 bg-brand-red text-white rounded">
            Klik Saya: {{ count }}
        </button>
    </div>
</template>

<script setup>
import { ref } from 'vue';
const count = ref(0);
</script>