/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import { createApp } from 'vue';
import { createPinia } from 'pinia';
import 'vue3-toastify/dist/index.css';
const pinia = createPinia()
const app = createApp({});
import products from './components/Product/list.vue';
app.use(pinia);
app.component('products', products);
app.mount('#app');
