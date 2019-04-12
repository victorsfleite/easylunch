/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('./plugins');
require('./filters');
require('./components');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    created() {
        this.$axios.interceptors.response.use(
            response => response,
            error => {
                if (error.response && error.response.status === 403) {
                    if (error.response.status === 403) {
                        this.$toasted.error('Você não tem permissão para executar esta operação');
                    }
                    if (error.response.status === 400) {
                        this.$toasted.error('Você não pode executar esta operação');
                    }
                }

                return Promise.reject(error);
            }
        );
    },
    mounted() {
        this.loadTooltips();
    },
    updated() {
        this.$nextTick(() => this.loadTooltips());
    },
    methods: {
        loadTooltips() {
            $('[data-tooltip]').tooltip();
        },
    },
});
