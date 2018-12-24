
import 'es6-promise/auto'
import Vuex from 'vuex'
import VueRouter from 'vue-router'
// import VueAtlas from 'vue-atlas'
// import 'vue-atlas/dist/vue-atlas.css'
import * as uiv from 'uiv'
import AdminPanel from './components/admin/adminPanel.vue'
import { sync } from 'vuex-router-sync'

import 'animate.css'

require('./bootstrap')

window.Vue.use(VueRouter)

window.Vue.use(Vuex)

// window.Vue.use(VueAtlas, 'en')
window.Vue.use(uiv, {prefix: 'uiv'})

window.Velocity = require('velocity-animate')

require('./bootVueComponents')

let router = require('./routes.js').genRoutes(window.Laravel.thisUrl)

let Store = require('./store.js')

window.Vue.component('admin-panel-component', AdminPanel)

window.Laravel.page.admin = {}

function genComponentData (data = null) {
  var vals = {}
  if (window.myUtils.testStr(data)) {
    vals = window.myUtils.JsonParseOrRetObj(data, {}, window.myUtils.dumpData)
  } else if (typeof data === 'object' && !window.myUtils.isEmpty(data)) {
    for (var i in data) {
      var tmp = {
        items: window.myUtils.getItemsFrom(data[i]),
        pagination: window.myUtils.getPagingFrom(data[i])
      }
      window._.set(vals, i, tmp)
    }
  }
  if (window.myUtils.testData(vals) && !window.myUtils.isEmpty(vals)) {
    return vals
  } else if (window._.has(vals, 'pages') &&
    window._.has(vals, 'sections') &&
    window._.has(vals, 'users') &&
    window._.has(vals, 'article')
  ) {
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
  // console.log(window.myUtils.dataToString(data))
  if (window.myUtils.testData(data) && !window.myUtils.isEmpty(data)) {
    if (typeof data === 'object' || Array.isArray(data)) {
      var res = []
      for (var i in data) {
        // console.log(window.myUtils.dataToString(i))
        if (window.myUtils.hasValueIn(data[i], 'value') &&
          window.myUtils.hasValueIn(data[i], 'children')
        ) {
          res.push(
            {
              value: window.myUtils.getValueFrom(data[i], 'value', null),
              children: Store.valToComponentArray(window.myUtils.getValueFrom(data[i], 'children', null))
            }
          )
        } else {
          res.push(
            {
              value: {
                name: window.myUtils.getValueFrom(data[i], 'name', window._.capitalize(i)),
                path: window.myUtils.getValueFrom(data[i], 'path', i),
                // component: Foo,
                pagination: window.myUtils.getPagingFrom(data[i])
              },
              children: Store.valToComponentArray(window.myUtils.getItemsFrom(data[i]))
            }
          )
        }
      }
      // console.log(window.myUtils.dataToString(res))
      return res
    } else if (window.myUtils.testStr(data)) {
      return genStoreData(window.myUtils.JsonParseOrRetObj(data))
    }
  } else {
    return []
  }
}

const initData = genComponentData(window.Laravel.admin)
// window.myUtils.dumpData(window.Laravel.admin)
// window.myUtils.dumpData(window._.omit(initData, ['article', 'header']))
let myStore = Store.makeStore(genStoreData(window._.omit(initData, ['article', 'header'])))

const unsynch = sync(myStore, router)

window.Laravel.page.admin.app = new window.Vue(
  {
    el: '#cms-app',
    router: router,
    store: myStore,
    template: '<admin-panel-component></admin-panel-component>',
    /* template: '<admin-panel-component v-bind="componentData"></admin-panel-component>',
    data: {
      initData: initData
    },
    computed: {
      componentData: () => {
        // return genComponentData(this.initData)
        return this.initData
      }
    }, */
    methods: {},
    beforeDestroy: function () {
      unsynch()
    }
  }
)
