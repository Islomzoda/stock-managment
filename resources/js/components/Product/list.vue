<template>
    <create></create>
    <div>
      <div class="container">
        <div class="row mt-3">
          <div class="col-12">
            <input v-model="searchQuery" type="text" class="form-control" placeholder="Поиск">
          </div>
        </div>
  
        <h2 class="mt-3">Список продуктов</h2>
        <div class="row">
          <template v-for="product in filteredProducts" :key="product.id" v-if="filteredProducts?.length">
            <sell :product="product"></sell>
          </template>
        </div>
  
        <div class="pagination mt-3">
          <button class="btn btn-primary" @click="loadPreviousPage" :disabled="!pagination.prevPage">Предыдущая страница</button>
          <span>Страница {{ pagination.currentPage }} из {{ pagination.totalPages }}</span>
          <button class="btn btn-primary" @click="loadNextPage" :disabled="!pagination.nextPage">Следующая страница</button>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import sell from './Sell.vue';
  import create from './create.vue';
  import axios from 'axios';
  
  export default {
    components: {
      create,
      sell
    },
    data() {
      return {
        products: [],
        pagination: {
          currentPage: 1,
          totalPages: 0,
          prevPage: null,
          nextPage: null
        },
        searchQuery: ''
      };
    },
    mounted() {
      this.fetchProducts();
    },
    computed: {
      filteredProducts() {
          return this.products;
      }
    },
    methods: {
      fetchProducts() {
        // Запрос к API для получения списка продуктов с пагинацией
        axios.get('/api/products', {
            params: {
              page: this.pagination.currentPage
            }
          })
          .then(response => {
            this.products = response.data.products;
            this.pagination = response.data.pagination;
          })
          .catch(error => {
            console.error(error);
          });
      },
      loadPreviousPage() {
        if (this.pagination.prevPage) {
          this.pagination.currentPage = this.pagination.prevPage;
          this.fetchProducts();
        }
      },
      loadNextPage() {
        if (this.pagination.nextPage) {
          this.pagination.currentPage = this.pagination.nextPage;
          this.fetchProducts();
        }
      },

      searchProducts() {
        axios.get('/api/products/search', {
            params: {
              query: this.searchQuery,
              page: 1 // Сбросить текущую страницу при поиске
            }
          })
          .then(response => {
            this.products = response.data.products;
            this.pagination = response.data.pagination;
          })
          .catch(error => {
            console.error(error);
          });
      }
    },
    watch: {
      searchQuery() {
        this.searchProducts();
      }
    }
  };
  </script>
  