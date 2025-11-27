import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'; // <--- Kita panggil lagi Vue

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue(), // <--- Kita aktifkan lagi Vue
    ],
    resolve: {
        alias: {
            // Ini penting biar Vue bisa jalan di Browser
            vue: 'vue/dist/vue.esm-bundler.js',
        },
    },
});