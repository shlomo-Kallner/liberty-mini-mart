
import 'es6-promise/auto'
import Vuex from 'vuex'
import VueRouter from 'vue-router'
import AdminPanel from './components/admin/adminPanel.vue'

require('./bootstrap')

window.Vue.use(VueRouter)

window.Vue.use(Vuex)

require('./bootVueComponents')

window.Vue.component('admin-panel-component', AdminPanel);

window.Laravel.page.admin = {}

function genComponentData (data) {
  if (typeof data === 'string') {
    var vals = window.myUtils.JsonParseOrRetObj(data, {}, window.myUtils.outputErrorsToConsole)
  } else if (typeof data === 'object') {
    var vals = {}
    for (var i in data) {
      if (typeof i === 'object') {
        var tmp = {
          items: window.myUtils.JsonParseOrRetObj(i.items, [], window.myUtils.outputErrorsToConsole),
          pagination: window.myUtils.JsonParseOrRetObj(i.pagination, [], window.myUtils.outputErrorsToConsole)
        }
        vals[i] = tmp
      }
    }
  }
  if (window._.size(vals) > 0) {
    return {
      initPages: vals.pages,
      initSections: vals.sections,
      initUsers: vals.users,
      initArticle: window.myUtils.getArticleData(
        vals.article, 'col-md-12', 'col-md-12'
      )
    }
  } else {
    return {
      initPages: {},
      initSections: {},
      initUsers: {},
      initArticle: {}
    }
  }
}
const router = require('./routes.js').default
const store = require('./store.js').default
window.Laravel.page.admin.app = new window.Vue(
  {
    router,
    store,
    template: '<admin-panel-component v-bind="componentData"></admin-panel-component>',
    data: {
      initData: window.Laravel.admin
    },
    computed: {
      componentData: () => {
        return genComponentData(this.initData)
      }
    },
    methods: {}
  }
).$mount('#cms-app')
