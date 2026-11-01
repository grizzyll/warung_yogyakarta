import './bootstrap';
import { createApp } from 'vue';
import CustomerOrder from './components/CustomerOrder.vue';

const app = createApp({});
app.component('customer-order', CustomerOrder);
app.mount('#customer-app');