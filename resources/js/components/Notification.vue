<template>
    <div v-if="message" class="notification" :class="[type]">
      {{ message }}
    </div>
  </template>
  
  <script>
  export default {
    props: {
      message: {
        type: String,
        default: '',
      },
      type: {
        type: String,
        default: 'success',
      },
    },
  };
  </script>
  
  <style scoped>
  .notification {
    padding: 10px;
    margin-bottom: 10px;
    color: #fff;
  }
  
  .success {
    background-color: green;
  }
  
  .error {
    background-color: red;
  }
  </style>
  