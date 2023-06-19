import { defineStore } from 'pinia';
import { toast } from 'vue3-toastify';

export const useProductsStore = defineStore('products', {
  state: () => ({
    products: [],
    pagination: {
      currentPage: 1,
      totalPages: 0,
      prevPage: null,
      nextPage: null
    }
  }),
  actions: {
    fetchProducts() {
      return axios.get('/api/products', {
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
        throw error;
      });
    },

    searchProducts(query) {
      return axios.get('/api/products/search', {
        params: {
          query,
          page: 1
        }
      })
      .then(response => {
        this.products = response.data.products;
        this.pagination = response.data.pagination;
      })
      .catch(error => {
        // console.error(error);
        throw error;
      });
    },
    createProduct(productData) {
        // Выполните запрос на сервер для создания товара, используя productData
        return axios.post('/api/product/create', productData)
          .then(response => {
            toast.success('Товар успешно добавлен', {
              autoClose: 1000
            });
            // Обновите список товаров после успешного создания
            this.fetchProducts();
          })
          .catch(error => {
            toast.error('Ошибка. Повторите попытку', {
              autoClose: 1000
            });
            // console.error(error);
          });
      },
  }
});
