import Vue from 'vue';

require('./globals');

// Menus
Vue.component('menus-index', require('./menus/index'));
Vue.component('menus-form', require('./menus/form'));
Vue.component('menu-show', require('./menus/show'));
