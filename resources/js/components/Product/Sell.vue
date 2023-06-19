<template>
    <!-- Button trigger modal -->
    <div class="col-12 col-md-4 col-sm-6 mt-2" data-bs-toggle="modal" :data-bs-target="`#sellProductModal${product.id}`">
      <div class="card product-card">
        <img :src="getImageUrl(product.id)" class="card-img-top" alt="Изображение товара" height="400" width="300">
        <div class="card-body">
          <h2 class="card-title">{{ product.name }}</h2>
          <div class="d-flex card-text justify-content-between">
            <span class="fs-3 fw-bold">{{ product.unit }}</span>
            <span class="fs-2 fw-bold">{{ product.price }} ₽</span>
            <span class="fs-3 fw-bold " :class="getTextColorClass()">{{ product.expireAt }}</span>
          </div>    
        </div>
      </div>
    </div>
  
    <!-- Modal -->
    <div class="modal fade" :id="`sellProductModal${product.id}`" tabindex="-1" :aria-labelledby="`sellProductModalLabel${product.id}`" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" :id="`sellProductModalLabel${product.id}`">Изменить товар</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="product-details">
              <div class="form-group">
                <label for="name">Имя</label>
                <div class="input-group">
                  <input type="text" id="name" v-model="product.name" class="form-control" @input="onInputChange('name')">
                  <button v-if="isFieldModified('name')" type="button" class="btn btn-success" @click="saveField('name')">Сохранить</button>
                </div>
              </div>
              <div class="form-group">
                <label for="description">Описание</label>
                <div class="input-group">
                  <textarea id="description" v-model="product.description" class="form-control" @input="onInputChange('description')"></textarea>
                  <button v-if="isFieldModified('description')" type="button" class="btn btn-success" @click="saveField('description')">Сохранить</button>
                </div>
              </div>
              <div class="form-group">
                <label for="unit">Единица измерения</label>
                <div class="input-group">
                <select id="unit" v-model="product.unit" class="form-control" required>
                  <option value="1/шт">1/шт</option>
                  <option value="100/гр">100/гр</option>
                  <option value="1/кг">1/кг</option>
                </select>
                  <button v-if="isFieldModified('unit')" type="button" class="btn btn-success" @click="saveField('unit')">Сохранить</button>
                </div>
              </div>
              <div class="form-group">
                <label for="price">Цена</label>
                <div class="input-group">
                  <input type="number" id="price" v-model="product.price" class="form-control" @input="onInputChange('price')">
                  <button v-if="isFieldModified('price')" type="button" class="btn btn-success" @click="saveField('price')">Сохранить</button>
                </div>
              </div>
              <div class="form-group">
                <label for="stock">Остаток на складе</label>
                <div class="input-group">
                  <input type="number" id="stock" v-model="product.stock" class="form-control" @input="onInputChange('stock')">
                  <button v-if="isFieldModified('stock')" type="button" class="btn btn-success" @click="saveField('stock')">Сохранить</button>
                </div>
              </div>
              <div class="form-group">
                <label for="expireAt">Срок годности</label>
                <div class="input-group">
                  <input type="date" id="expireAt" v-model="product.expireAt" class="form-control" @input="onInputChange('expireAt')">
                  <button v-if="isFieldModified('expireAt')" type="button" class="btn btn-success" @click="saveField('expireAt')">Сохранить</button>
                </div>
              </div>
                    <div class="form-group">
                    <label for="salesCount">Количество продаж</label>
                    <div class="input-group">
                        <input type="number" id="salesCount" v-model="salesCount" class="form-control">
                        <button v-if="salesCount != 0" type="button" class="btn btn-success" @click="updateSell">Сохранить</button>
                    </div>
                    </div>  
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отменить</button>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  import { toast } from 'vue3-toastify';
  import { useProductsStore } from '../../stores/products';

  export default {
    props: {
      product: {
        type: Object,
        required: true
      },
    },
    data() {
      return {
        initialProduct: null,
        salesCount: 0
      };
    },
    computed:{
        initialProductComputed(){
            return this.initialProduct = JSON.parse(JSON.stringify(this.product));
        }
    },
    created() {
        this.initialProductComputed
    },
    methods: {
      getImageUrl(productId) {
        return `/storage/products/${productId}.png`;
      },
      isFieldModified(field) {
        return this.product[field] !== this.initialProduct[field];
      },
      onInputChange(field) {
        // Обработчик изменения значения в инпуте
      },
      updateSell(){
        const store = useProductsStore();
        axios.post('api/product/sell',{
            product: this.product,
            quantity: this.salesCount
        }).then(res => {
            store.fetchProducts();
            toast.success('Остатов успешно обнавлен', {
            autoClose: 1000
            })
        }).catch(e => {
            toast.error('Ошибка Повторите попытку', {
            autoClose: 1000
            })
            console.log(e)
        }).finally(() => {
            setTimeout(() => {
            this.initialProduct.stock = this.product.stock
            this.salesCount = 0;
           }, 1000)
        })
      },
      getTextColorClass() {
        const currentDate = new Date();
        const expireDate = new Date(this.product.expireAt);
        const timeDifference = expireDate - currentDate;
        const oneWeek = 7 * 24 * 60 * 60 * 1000; // Временной интервал для недели
        const oneMonth = 30 * 24 * 60 * 60 * 1000; // Временной интервал для месяца

        if (timeDifference < oneWeek) {
            return 'text-danger'; // Красный цвет текста для менее недели
        } else if (timeDifference < oneMonth) {
            return 'text-warning'; // Оранжевый цвет текста для менее месяца
        } else {
            return 'text-success'; // Зеленый цвет текста для более месяца
        }
    },
      saveField(field) {
        console.log(this.product[field]);
        axios.post('api/product/update',{
            field: field,
            value: this.product[field],
            productId: this.product.id
        }).then(res => {
            this.initialProduct[field] = this.product[field]
            toast.success(field +'товара успешно обнавлен', {
            autoClose: 1000
            })
        }).catch(e => {
            console.log(e)
            toast.error('Ошибка Повторите попытку', {
            autoClose: 1000
            })
        })
        // Сохранение поля на сервере
      }
    }
  };
  </script>
  