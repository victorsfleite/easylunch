import Vue from 'vue';
import axios from 'axios';
import Toasted from 'vue-toasted';
import moment from 'moment-timezone';
import { loadProgressBar } from 'axios-progress-bar';
import VCalendar from 'v-calendar';
import VueAvatar from 'vue-avatar';

// Bootstrap Vue components
import vBTooltip from 'bootstrap-vue/es/directives/tooltip/tooltip';

// DevSquad
import DevsquadUi from '@elitedevsquad/ui';
import Form from '@/plugins/form';

Vue.directive('tooltip', vBTooltip);
Vue.use(DevsquadUi);

window.moment = moment;
window.Form = Form;

Vue.prototype.$user = Globals.user;
Vue.prototype.$route = route;
Vue.prototype.$axios = axios;
Vue.prototype.$obj_get = (obj, str) => {
    return str.split('.').reduce((a, c) => (a ? a[c] : null), obj);
};

loadProgressBar();

Vue.use(Toasted, {
    iconPack: 'fontawesome',
    duration: 5000,
});

Vue.use(VCalendar, { locale: 'pt_BR', firstDayOfWeek: 1 });
Vue.component('avatar', VueAvatar);
