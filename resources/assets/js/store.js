
import Vuex from 'vuex'
import { Stack } from './lib/LibertyStack.js'
import { ComponentTree } from "./lib/LaravelComponentTree.js"

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
    }
  }
})