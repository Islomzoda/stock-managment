/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import { createApp } from 'vue';
import store from './store.js';
const app = createApp({});
app.config.globalProperties.$notify = function (message, type = 'success') {
    app.config.globalProperties.$store.dispatch('showNotification', { message, type });
};  
import products from './components/Product/list.vue';
app.provide('store', store);
app.component('products', products);

app.mount('#app');
