
import Vue from 'vue'
import Vuex from 'vuex'
import VueRouter from 'vue-router'

import { Stack } from './lib/LibertyStack.js'
import { ComponentTree } from './lib/LaravelComponentTree.js'
import _ from 'lodash'
import myUtils from './utils'

Vue.use(VueRouter)
Vue.use(Vuex)

const Foo = { template: '<div>Hello World!</div>' }

export function valToComponent (data = null, defPath = '', defComp = Foo, defPaging = null) {
  if (typeof data === 'object') {
    if (!_.has(data, 'path') && _.has(data, 'url')) {
      data.path = data.url
    } else if (!_.has(data, 'path') && !_.has(data, 'url')) {
      data.path = defPath
    }
    if (!_.has(data, 'component')) {
      data.component = defComp
    }
    if (!_.has(data, 'pagination')) {
      data.pagination = myUtils.genPagingData(defPaging, 8)
    }
    return data
  } else {
    return {
      path: defPath,
      component: defComp,
      pagination: defPaging
    }
  }
}

export function valToComponentArray (data) {
  // myUtils.dumpData(data)
  if (data !== null && data !== undefined && Array.isArray(data)) {
    var res = []
    for (var i in data) {
      if (data[i] instanceof ComponentTree) {
        res.push(data[i])
      } else if (_.has(data[i], 'value') && _.has(data[i], 'children')) {
        var val = myUtils.getValueFrom(data[i], 'value')
        var chd = myUtils.getValueFrom(data[i], 'children', [])
        if (myUtils.testData(val)) {
          res.push(new ComponentTree(val, chd))
        }
      } else {
        res.push(new ComponentTree(valToComponent(data[i])))
      }
    }
    // myUtils.dumpData(res)
    return res
  } else {
    return []
  }
}

export function makeStore (data) {
  var compInitVal = new ComponentTree(
    {
      name: 'root',
      path: '/'
    },
    valToComponentArray(data),
    null
  )
  return new Vuex.Store({
    state: {
      breadcrumbs: new Stack([]),
      components: compInitVal
    },
    mutations: {
      pushCrumb: function (state, crumb) {
        state.breadcrumbs.push(crumb)
      },
      popCrumb: function (state, crumb) {
        if (state.breadcrumbs.top() === crumb) {
          state.breadcrumbs.pop()
        }
      },
      setCrumbs: function (state, payload) {
        var r = payload.route
        var c = state.getters.findComponent(r.path, (cv, p) => cv.path === p)
        var arr = []
        while (myUtils.testData(c)) {
          arr.push(c.value)
          c = c.parent
        }
        while (arr.length > 0) {
          state.breadcrumbs.push(arr.pop())
        }
      },
      setComponents: function (state, payload) {
        if (Array.isArray(payload) || typeof payload === 'object') {
          for (var i in payload) {
            state.components.push(payload[i])
          }
        }
      },
      pushComponent: (state, payload) => {
        var { component, nt } = payload
        var tree = state.getters.findComponent(component)
        if (tree !== null && tree !== undefined) {
          tree.push(nt)
        }
      }
    },
    getters: {
      components: (state) => {
        var res = []
        var ch = state.components.children
        for (var i of ch) {
          res.push(i.value)
        }
        return res
      },
      findComponent: (state) => (value, comp = null) => {
        return state.components.findSubTreeWithValue(value, comp)
      },
      findTab: state => (value, comp = null) => {
        var c = state.components.children
        var res = null
        for (var i of c) {
          if (i.value === value || (typeof comp === 'function' &&
          comp(i.value, value))) {
            res = i
            break
          }
        }
        return res
      },
      getBreadcrumbs: (state) => state.breadcrumbs.data,
      getComponentChildrenValues: (state, getters) => (value, comp = null) => {
        var t = getters.findComponent(value, comp)
        var res = []
        if (myUtils.testData(t)) {
          for (var i of t.children) {
            res.push(i.value)
          }
        }
        return res
      }
    }
  })
}
