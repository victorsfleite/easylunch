import Vue from 'vue';
import Laroute from './laroute';
import axios from 'axios';

Vue.prototype.$route = Laroute.route;
Vue.prototype.$axios = axios;
