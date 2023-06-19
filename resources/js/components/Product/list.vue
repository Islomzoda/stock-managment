<template>
    <div>
        <create></create>
      <div class="container">
        <div class="row mt-3">
          <div class="col-12">
            <input v-model="searchQuery" type="text" class="form-control" placeholder="Поиск">
          </div>
        </div>
  
        <h2 class="mt-3">Список продуктов</h2>
        <div class="row">
          <template v-for="product in filteredProducts" :key="product.id">
            <sell :product="product"></sell>
          </template>
        </div>
  
        <div class="pagination mt-3 d-flex justify-content-between">
          <button class="btn btn-primary" @click="loadPreviousPage" :disabled="!pagination.prevPage">Предыдущая страница</button>
          <span>Страница {{ pagination.currentPage }} из {{ pagination.totalPages }}</span>
          <button class="btn btn-primary" @click="loadNextPage" :disabled="!pagination.nextPage">Следующая страница</button>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import { defineComponent, ref, computed } from 'vue';
  import create from './create.vue';
  import { useProductsStore } from '../../stores/products';
  import sell from './Sell.vue';
  
  export default defineComponent({
    components: {
      sell,
      create
    },
    setup() {
      const productsStore = useProductsStore();
      const filteredProducts = computed(() => productsStore.products);
      const pagination = ref({
        currentPage: 1,
        totalPages: 0,
        prevPage: null,
        nextPage: null
      });
      const searchQuery = ref('');
  
      const fetchProducts = () => {
        productsStore.fetchProducts()
      };
  
      const loadPreviousPage = () => {
        if (pagination.value.prevPage) {
          pagination.value.currentPage = pagination.value.prevPage;
          fetchProducts();
        }
      };
  
      const loadNextPage = () => {
        if (pagination.value.nextPage) {
          pagination.value.currentPage = pagination.value.nextPage;
          fetchProducts();
        }
      };
  
      const searchProducts = () => {
        productsStore.searchProducts(searchQuery.value)
          .then(response => {
            productsStore.setProducts(response.data.products);
            pagination.value = response.data.pagination;
          })
          .catch(error => {
            console.error(error);
          });
      };
      fetchProducts();
      return {
        filteredProducts,
        pagination,
        searchQuery,
        fetchProducts,
        loadPreviousPage,
        loadNextPage,
        searchProducts
      };
    }
  });
  </script>
  