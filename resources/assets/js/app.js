
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import { LaravelAlert } from './lib/LaravelAlert'

// import { Pagination } from 'vue-pagination-2'


require('./bootstrap');

window.Vue = require('vue');
window.url = require('url');
// window.Vue.component('pagination', Pagination);

const uuidv5 = require('uuid/v5');
const uuidv5 = require('uuid/v5');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */



/*
  window.Vue.component('example', require('./components/Example.vue'));
  const app = new Vue({
      el: '#app'
  });
 */

window.Vue.component('dismissable-alert', require('./components/dismissable-alert.vue'));
window.Vue.component('cart-component', require('./components/cart.vue'));
window.Vue.component('boot-pagination', require('./components/bootPaginator.vue'));


window.Laravel.page.alert = new LaravelAlert(window.Laravel.alert);

window.Laravel.masterAlert = new window.Vue({
  el: '#masterPageAlertContainer',
  template: '<dismissable-alert v-bind:initAlert="alert"></dismissable-alert>',
  data: {
    alertData: window.Laravel.page.alert.getData()
    // initAlert: new LaravelAlert(window.Laravel.alert)
  },
  created: function () {
    // `this` points to the vm instance
    // console.log('alert is: ' + this.alert);
  },

  computed: {
    alert: {
      set: function (data) {
        this.alertData = data;
      },
      get: function () {
        return this.alertData;
      }
    }
  }
});

window.Laravel.page.setAlert = function (data) {
  window.Laravel.page.alert = new LaravelAlert(data);
  window.Laravel.masterAlert.alert = window.Laravel.page.alert.getData();
};


window.Laravel.masterCart = new window.Vue(
  {
    el: '#topCartComp',
    template: '<cart-component v-bind:initCart="cart" v-bind:baseUrl="baseUrl"></cart-component>',
    data: {
      baseUrl: window.Laravel.baseUrl,
      cartData: JSON.parse(window.Laravel.cart)
    },
    computed: {
      cart: {
        get: function () {
          return this.cartData;
        },
        set: function (data) {
          if (typeof data === 'string') {
            this.cartData = JSON.parse(data);
          } else if (typeof data == 'object') {
            this.cartData = data;
          }
        }
      }
    }
  }
);

window.Laravel.page.setCart = function (data) {
  window.Laravel.page.cart = data;
  window.Laravel.masterCart.cartData = data;
};

window.Laravel.page.paginator = new window.Vue(
  {
    el: '#pageWidePagination',
    template: '<boot-pagination v-bind=""></boot-pagination>',
    data: {
      urlObj: window.url.parse(window.Laravel.thisUrl),
      pagingFor: 'content',
      pagingData: {
        numPages: Number,
        currentPage: Number,
        pagesPerView: Number,
        itemsPerPage: Number,
        totalItems: Number
      }
    },
    methods: {
      genUrl: function (pageNum, viewNum) {
        return window.url.format({
          protocol: this.urlObj.protocol,
          host: this.urlObj.host,
          pathname: this.urlObj.pathname,
          query: {
            pageNum: pageNum,
            viewNum: viewNum,
            pagingFor: this.pagingFor
          }
        });
      }
    }
  }
);