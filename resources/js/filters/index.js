import Vue from 'vue';
import date from './date';
import from_now from './from_now';

Vue.filter('date', date);
Vue.filter('from_now', from_now);
