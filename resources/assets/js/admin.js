
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

function JsonParseOrRetObj (data, def = {}, err = null) {
  if (typeof data === 'string') {
    var res = def;
    try {
      res = JSON.parse(data)
    } catch (error) {
      var te = error
      try {
        res = window.Json5.parse(data)
      } catch (error) {
        if (typeof err === 'function') {
          err([te, error])
        } else {
          throw new Error(te.message + error.message)
        }
      }
    }
    return res
  } else if (typeof data === 'object') {
    return data
  } else {
    return def
  }
}

function outputErrorsToConsole (error) {
  var [e1, e2] = error
  console.log(e1.toString() + e2.toString())
}

function genComponentData (data) {
  if (typeof data === 'string') {
    var vals = JsonParseOrRetObj(data, {}, outputErrorsToConsole)
  } else if (typeof data === 'object') {
    var vals = {}
    for (var i in data) {
      if (typeof i === 'object') {
        var tmp = {
          items: JsonParseOrRetObj(i.items, [], outputErrorsToConsole),
          pagination: JsonParseOrRetObj(i.pagination, [], outputErrorsToConsole)
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
