
window.Vue.component('dismissable-alert', require('./components/lib/dismissable-alert.vue'))
window.Vue.component('cart-component', require('./components/metronic/cart.vue'))
window.Vue.component('boot-pagination', require('./components/lib/bootPaginator.vue'))
window.Vue.component('boot-carousel', require('./components/lib/bootCarousel.vue'))
window.Vue.component('boot-touchspin', require('./components/lib/bootTouchspin.vue'))
window.Vue.component('cart-details-component', require('./components/metronic/cartDetails.vue'))

window.Laravel.page.alert = new window.Laravel.LaravelAlert(window.Laravel.alert)

window.Laravel.masterAlert = new window.Vue(
  {
    el: '#masterPageAlertContainer',
    template: '<dismissable-alert v-bind:initAlert="alert"></dismissable-alert>',
    data: {
      alertData: window.Laravel.page.alert.getData()
      // initAlert: new LaravelAlert(window.Laravel.alert)
    },
    created: function () {
      // `this` points to the vm instance
      // console.log('alert is: ' + this.alert)
    },
    computed: {
      alert: {
        set: function (data) {
          if (typeof data === 'object') {
            this.alertData = data
          } else if (typeof data === 'string') {
            this.alertData = JSON.parse(data)
          }
        },
        get: function () {
          return this.alertData
        }
      }
    }
  }
)

window.Laravel.page.setAlert = function (data) {
  window.Laravel.page.alert = new window.Laravel.LaravelAlert(data);
  window.Laravel.masterAlert.alert = window.Laravel.page.alert.getData();
}

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
          return this.cartData
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
)

window.Laravel.page.setCart = function (data) {
  window.Laravel.page.cart = data;
  window.Laravel.masterCart.cartData = data;
}
