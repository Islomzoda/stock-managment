import { reactive } from 'vue';

// Создайте объект, содержащий ваше состояние приложения
const state = reactive({
  notifications: [],
});

// Определите методы для взаимодействия с состоянием
const mutations = {
  addNotification(notification) {
    state.notifications.push(notification);
  },
  removeNotification(notification) {
    state.notifications = state.notifications.filter((n) => n !== notification);
  },
};

// Создайте объект глобального стора, содержащий состояние и методы
const store = {
  state,
  mutations,
};

export default store;
