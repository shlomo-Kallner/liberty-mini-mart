
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import { LaravelAlert } from './lib/LaravelAlert'

require('./bootstrap');

window.Vue = require('vue');



/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example', require('./components/Example.vue'));

/*
const app = new Vue({
    el: '#app'
});
 */

Vue.component('dismissable-alert', require('./components/dismissable-alert.vue'));
Vue.component('cart-component', require('./components/cart.vue'));


window.Laravel.page.alert = new LaravelAlert(window.Laravel.alert);


window.Laravel.masterAlert = new Vue({
  el: '#masterPageAlertContainer',
  template: '<dismissable-alert v-bind:initAlert="alert"></dismissable-alert>',
  data: {
    alertData: window.Laravel.page.alert.getData()
    // initAlert: new LaravelAlert(window.Laravel.alert)
  },
  created: function () {
    // `this` points to the vm instance
    //console.log('alert is: ' + this.alert);
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


window.Laravel.masterCart = new Vue(
  {
    el: '#topCartComp',
    template: '<cart-component v-bind:initCart="cart" v-bind:baseUrl="baseUrl"></cart-component>',
    data: {
      baseUrl: window.Laravel.baseUrl,
      cartData: JSON.parse(window.Laravel.cart)
    },
    computed: {
      cart : {
        get: function () {
          return this.cartData;
        },
        set: function (data) {
          if (typeof data == 'string') {
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
  window.Laravel.masterCart.cart = window.Laravel.page.cart;
};