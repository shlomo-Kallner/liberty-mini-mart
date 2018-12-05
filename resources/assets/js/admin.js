
import 'es6-promise/auto'
import Vuex from 'vuex'
import VueRouter from 'vue-router'
import AdminPanel from './components/admin/adminPanel.vue'

require('./bootstrap')

window.Vue.use(VueRouter)

window.Vue.use(Vuex)

require('./bootVueComponents')

const router = require('./routes.js').default

const Store = require('./store.js')

window.Vue.component('admin-panel-component', AdminPanel)

window.Laravel.page.admin = {}

function genComponentData (data = null) {
  var vals = {}
  if (typeof data === 'string') {
    vals = window.myUtils.JsonParseOrRetObj(data, {}, window.myUtils.dumpData)
  } else if (typeof data === 'object') {
    for (var i in data) {
      if (typeof data[i] === 'object') {
        var tmp = {
          items: window.myUtils.JsonParseOrRetObj(data[i].items, [], window.myUtils.dumpData),
          pagination: window.myUtils.JsonParseOrRetObj(data[i].pagination, [], window.myUtils.dumpData)
        }
        vals[i] = tmp
      } else if (typeof data[i] === 'string') {
        vals[i] = window.myUtils.JsonParseOrRetObj(data[i], {}, window.myUtils.dumpData)
      }
    }
  }
  if (window._.size(vals) > 0) {
    return vals
  } else if (false) {
    return {
      initPages: vals.pages,
      initSections: vals.sections,
      initUsers: vals.users,
      initArticle: window.myUtils.getArticleData(
        vals.article, 'col-md-12', 'col-md-12'
      )
    }
  } else {
    return {}
  }
}

function genStoreData (data = null) {
  if (data !== null || data !== undefined) {
    if (Array.isArray(data)) {
      var res = []
      for (var i in data) {
        res.push(
          {
            value: {
              name: window._.capitalize(i),
              path: i,
              // component: Foo,
              pagination: window.myUtils.getPagingFrom(data[i])
            },
            children: Store.valToComponentArray(window.myUtils.getItemsFrom(data[i]))
          }
        )
      }
    }
  } else {
    return []
  }
}

const initData = genComponentData(window.Laravel.admin)

const myStore = Store.makeStore(genStoreData(window._.omit(initData, ['article', 'header'])))

window.Laravel.page.admin.app = new window.Vue(
  {
    router: router,
    store: myStore,
    template: '<admin-panel-component v-bind="componentData"></admin-panel-component>',
    data: {
      initData: initData
    },
    computed: {
      componentData: () => {
        return genComponentData(this.initData)
      }
    },
    methods: {}
  }
).$mount('#cms-app')
