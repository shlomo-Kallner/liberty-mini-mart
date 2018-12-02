
import 'es6-promise/auto'
import Vuex from 'vuex'
import VueRouter from 'vue-router'

window.Vue.use(VueRouter)

window.Vue.use(Vuex)

window.Vue.component(
  'admin-panel-component',
  require('./components/admin/adminPanel.vue')
);
window.Laravel.page.admin = {}

var setAdmin = true // ? true : window.jQuery('#cms-app').length > 0 

if (setAdmin) {
  window.Laravel.page.admin.router = require('./routes')
  window.Laravel.page.admin.store = require('./store')
  window.Laravel.page.admin.app = new window.Vue(
    {
      el: '#cms-app',
      router: window.Laravel.page.admin.router,
      store: window.Laravel.page.admin.store,
      template: '<admin-panel-component v-bind="thisdata"></admin-panel-component>',
      data: {
        initData: JSON.parse(window.Laravel.admin)
      },
      computed: {
        thisdata: function () {
          return {
            initPages: this.initData.pages,
            initSections: this.initData.sections,
            initUsers: this.initData.users,
            initArticle: window.myUtils.getArticleData(this.initData.article, 'col-md-12', 'col-md-12')
          }
        }
      }
    }
  )
}
