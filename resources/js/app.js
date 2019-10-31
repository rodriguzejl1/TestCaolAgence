
import './bootstrap'

import Vue from 'vue'
import Vuex from 'vuex'
import StoreData from './store'
import {routes} from './routes'
import VueRouter from 'vue-router'
import vuetify from './plugins/vuetify'
import App from './App.vue'

import numeral from 'numeral'
import numFormat from 'vue-filter-number-format'
import Vuelidate from 'vuelidate'


Vue.filter('numFormat', numFormat(numeral))
Vue.use(Vuelidate)
Vue.use(VueRouter)
Vue.use(Vuex)
Vue.use(require('vue-moment'));
const moment = require('moment')
require('moment/locale/es')

const store = new Vuex.Store(StoreData);

const router = new VueRouter({
  routes,
  mode:'history'
});

const app = new Vue({
  router,
  store,
  vuetify,
  el: '#app',
  render: (h) => h(App)
});
