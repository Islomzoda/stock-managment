<template>
  <!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createProductModal">
  Добавить
</button>

<!-- Modal -->
<div class="modal fade" id="createProductModal" tabindex="-1" aria-labelledby="createProductModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="createProductModalLabel">Добавить товар</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div>
              <div class="mb-3">
                <label for="name">Название товара:</label>
                <input type="text" id="name" v-model="product.name" class="form-control" required>
              </div>
              <div class="mb-3">
                <label for="description">Описание:</label>
                <textarea id="description" v-model="product.description" class="form-control" required></textarea>
              </div>
              <div class="mb-3">
                <label for="unit">Единица измерения:</label>
                <select id="unit" v-model="product.unit" class="form-control" required>
                  <option value="1/шт">1/шт</option>
                  <option value="100/гр">100/гр</option>
                  <option value="1/кг">1/кг</option>
                </select>
              </div>
              <div class="mb-3">
                <label for="price">Цена:</label>
                <input type="number" id="price" v-model="product.price" class="form-control" required>
              </div>
              <div class="mb-3">
                <label for="stock">Количество:</label>
                <input type="number" id="stock" v-model="product.stock" class="form-control" required>
              </div>
              <div class="mb-3">
                <label for="expireAt">Дата истечения срока годности:</label>
                <input type="date" id="expireAt" v-model="product.expireAt" class="form-control" required>
              </div>
              <div class="mb-3">
                <label for="image">Изображение:</label>
                <input type="file" id="image" ref="image" class="form-control-file" accept="image/*">
              </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отменить</button>
        <button type="button" class="btn btn-primary" @click="submitForm">Сохранить</button>
      </div>
    </div>
  </div>
</div>
</template>

<script>
import { toast } from 'vue3-toastify';
import { useProductsStore } from '../../stores/products';
export default {
  data() {
    return {
      product: {
        name: '',
        description: '',
        unit: '',
        price: null,
        stock: null,
        expireAt: null,
        image: null
      }
    };
  },
  methods: {
    submitForm() {
      const productStore = useProductsStore()
      const formData = new FormData();

      formData.append('name', this.product.name);
      formData.append('description', this.product.description);
      formData.append('unit', this.product.unit);
      formData.append('price', this.product.price);
      formData.append('stock', this.product.stock);
      formData.append('expireAt', this.product.expireAt);
      formData.append('image', this.$refs.image.files[0]);

      // Отправка данных на сервер
      productStore.createProduct(formData)
    }
  }
};
</script>