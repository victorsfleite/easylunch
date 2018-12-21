import Vue from 'vue';
import date from './date';
import from_now from './from_now';
import capitalize from './capitalize';

Vue.filter('date', date);
Vue.filter('from_now', from_now);
Vue.filter('capitalize', capitalize);
