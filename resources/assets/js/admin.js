
import 'es6-promise/auto'
import Vuex from 'vuex'
import VueRouter from 'vue-router'
import AdminPanel from './components/admin/adminPanel.vue'

require('./bootstrap')

window.Vue.use(VueRouter)

window.Vue.use(Vuex)

window.Vue.component('admin-panel-component', AdminPanel);

window.Laravel.page.admin = {}

window.Laravel.page.admin.app = new window.Vue(
  {
    el: '#cms-app',
    router: require('./routes.js'),
    store: require('./store.js'),
    template: '<admin-panel-component v-bind="initData"></admin-panel-component>',
    data: {
      initData: this.genComponentData(window.Laravel.admin)
    },
    computed: {},
    methods: {
      genComponentData: data => {
        var vals = JSON.parse(data)
        return {
          initPages: vals.pages,
          initSections: vals.sections,
          initUsers: vals.users,
          initArticle: window.myUtils.getArticleData(
            vals.article, 'col-md-12', 'col-md-12'
          )
        }
      }
    }
  }
)
