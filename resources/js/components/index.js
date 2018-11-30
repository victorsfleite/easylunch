import Vue from 'vue';

require('./globals');

// User Account Settings
Vue.component('profile-form', require('./users/settings/profile-form'));
Vue.component('password-form', require('./users/settings/password-form'));

// Menus
Vue.component('menus-index', require('./menus/index'));
Vue.component('menus-form', require('./menus/form'));
Vue.component('menu-show', require('./menus/show'));
Vue.component('orders-index', require('./orders/index'));
Vue.component('orders-form', require('./orders/form'));
