
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


window.Laravel.page.alert = new LaravelAlert(window.Laravel.alert);

var masterAlert = new Vue({
  el: '#masterPageAlertContainer',
  template: '<dismissable-alert v-bind:initAlert="alert"></dismissable-alert>',
  data: {
    alert: window.Laravel.page.alert.getData()
    // initAlert: new LaravelAlert(window.Laravel.alert)
  },
  created: function () {
    // `this` points to the vm instance
    console.log('alert is: ' + this.alert);
  }
});
