require('./bootstrap');

import Vue from 'vue';
import VueRouter from 'vue-router';
import { BootstrapVue } from 'bootstrap-vue'

Vue.use(VueRouter);
Vue.use(BootstrapVue);


import App from './components/App.vue';
import router from './routes.js'

Vue.component('app', require('./components/App.vue').default);

const app = new Vue({
    el: '#app',
    component:{ App} ,
    router,
});
