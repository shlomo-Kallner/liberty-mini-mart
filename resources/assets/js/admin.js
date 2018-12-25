
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

// window.myUtils.dumpData(window.Laravel.admin)
const initData = window.myUtils.genComponentData(window.Laravel.admin)
// window.myUtils.dumpData(initData)
const includedData = window._.omit(initData, ['article', 'header'])
// window.myUtils.dumpData(includedData)
const storeData = window.myUtils.genStoreData(includedData)
// window.myUtils.dumpData(storeData)
let myStore = Store.makeStore(storeData)

const unsynch = sync(myStore, router)

window.Laravel.page.admin.app = new window.Vue(
  {
    el: '#cms-app',
    router: router,
    store: myStore,
    template: '<admin-panel-component></admin-panel-component>',
    beforeDestroy: function () {
      unsynch()
    }
  }
)
