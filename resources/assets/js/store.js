
import Vuex from 'vuex'
import { Stack } from './lib/LibertyStack.js'
import { ComponentTree } from './lib/LaravelComponentTree.js'

export default new Vuex.Store({
  state: {
    breadcrumbs: new Stack([]),
    components: new ComponentTree(null)
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
    setComponents: function (state, payload) {
      if (Array.isArray(payload)) {
        for (var i in payload) {
          state.components.push(i)
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
      for (var i in state.components.getChildren()) {
        res.push(i.value())
      }
      return res
    },
    findComponent: (state) => (value, comp = null) => {
      return state.components.findSubTreeWithValue(value, comp)
    },
    findTab: state => (value, comp = null) => {
      var c = state.components.getChildren()
      var res = null
      for (var i in c) {
        if (i.value() === value || (typeof comp === 'function' &&
        comp(i.value(), value))) {
          res = i
          break
        }
      }
      return res
    }
  }
})