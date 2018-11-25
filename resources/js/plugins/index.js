import Vue from 'vue';
import Laroute from './laroute';
import axios from 'axios';
import Toasted from 'vue-toasted';
import moment from 'moment-timezone';

window.moment = moment;

Vue.prototype.$route = Laroute.route;
Vue.prototype.$axios = axios;
Vue.prototype.$obj_get = (obj, str) => {
    return str.split('.').reduce((a, c) => a ? a[c] : null, obj);
}

Vue.use(Toasted, {
    iconPack: 'fontawesome',
    duration: 5000,
});
