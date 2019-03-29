import Vue from 'vue';

require('./globals');

// User Account Settings
Vue.component('profile-form', require('./users/settings/profile-form').default);
Vue.component('password-form', require('./users/settings/password-form').default);

// Menus
Vue.component('menus-index', require('./menus/index').default);
Vue.component('menus-form', require('./menus/form').default);
Vue.component('menu-show', require('./menus/show').default);
// Orders
Vue.component('orders-index', require('./orders/index').default);
Vue.component('orders-form', require('./orders/form').default);
Vue.component('orders-report', require('./orders/report').default);
Vue.component('users-index', require('./users/index').default);
Vue.component('users-form', require('./users/form').default);
