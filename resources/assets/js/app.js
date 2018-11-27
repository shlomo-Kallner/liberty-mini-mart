
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
// import { LaravelAlert } from './lib/LaravelAlert'
window.Laravel.LaravelAlert = require('./lib/LaravelAlert').default;

// import { Pagination } from 'vue-pagination-2'


require('./bootstrap');

window.Vue = require('vue');
window.url = require('url');
window.myUtils = require('./utils').default;
// window.Vue.component('pagination', Pagination);

// let uuidv3 = require('uuid/v3');
// let uuidv5 = require('uuid/v5');

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
window.Vue.component('boot-carousel', require('./components/bootCarousel.vue'));

window.Laravel.page.alert = new window.Laravel.LaravelAlert(window.Laravel.alert);

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
        if (typeof data === 'object') {
          this.alertData = data;
        } else if (typeof data === 'string') {
          this.alertData = JSON.parse(data);
        }
      },
      get: function () {
        return this.alertData;
      }
    }
  }
});

window.Laravel.page.setAlert = function (data) {
  window.Laravel.page.alert = new window.Laravel.LaravelAlert(data);
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
          } else if (typeof data === 'object') {
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
if (window.jQuery('#masterPagination').length > 0) {
  window.Laravel.page.masterPaginator = new window.Vue(
    {
      el: '#masterPagination',
      template: '<boot-pagination v-bind="pagingData" @paging-event="doPaging"></boot-pagination>',
      data: {
        urlObj: window.url.parse(window.Laravel.thisUrl),
        paging: JSON.parse(window.Laravel.pagination),
        pageAsync: false,
        pagingFunc: function (pUrl) {
          // for now, no-op..
          return pUrl;
        }
      },
      computed: {
        pagingFor: function () {
          if (this.paging !== undefined && window._.size(this.paging) > 0) {
            return this.paging.pagingFor;
          } else {
            return '';
          }
        },
        pagingData: function () {
          return window.myUtils.getPagingData(this.paging);
        }
      },
      methods: {
        genUrl: function (pageNum, viewNum) {
          return window.myUtils.genUrl(
            this.urlObj, pageNum, viewNum, this.pagingFor
          );
        },
        doPaging: function (pageNum, viewNum) {
          var pUrl = this.genUrl(pageNum, viewNum);
          if (this.pageAsync) {
            // for now, no-op..
            this.pagingFunc(pUrl);
          } else {
            window.location.assign(pUrl);
          }
        }
      }
    }
  );
}
