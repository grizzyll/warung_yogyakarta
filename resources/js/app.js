import './bootstrap';
import { createApp } from 'vue';

// Import Komponen Kita
import PosComponent from './components/PosComponent.vue';
import KitchenComponent from './components/KitchenComponent.vue';

// Inisialisasi Vue
const app = createApp({});

// Daftarkan Komponen
app.component('pos-component', PosComponent);
app.component('kitchen-component', KitchenComponent);

// Tempelkan ke HTML
app.mount('#app');