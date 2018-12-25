<template>
  <div class="row padding-top-5">
    <div v-if="hasCrumbs" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <ol v-if="version === 1" class="breadcrumb pull-left">
          <li :class="{active: crumbs.length === 0}">
              <router-link to="/">
                  {{this.getPreText}} {{origin}} {{this.getPostText}}
              </router-link>
          </li>
          <li v-for="(item, index) in crumbs" 
              :key="index"
              :class="{active: current === item.name}"
          >
              <router-link :to="item.path">
                  {{this.getPreText}} {{item.name}} {{this.getPostText}}
              </router-link>
          </li>
      </ol>
      <uiv-breadcrumbs v-else class="pull-left">
        <uiv-breadcrumb-item v-for="(item, idx) in crumbs" 
          :key="idx" :class="{active: crumbs.length === (idx + 1)}"
          :to="item.path">
          {{this.getPreText}} {{item.name}} {{this.getPostText}}
        </uiv-breadcrumb-item>
      </uiv-breadcrumbs>
    </div>
  </div>
</template>

<script>

  import Vue from 'vue'
  import Vuex from 'vuex'
  import VueRouter from 'vue-router'
  import {Breadcrumbs as UivBreadcrumbs, BreadcrumbItem as UivBreadcrumbItem} from 'uiv'

  Vue.use(VueRouter)
  Vue.use(Vuex)

  export default {
    name: 'boot-breadcrumbs-component',
    props: {
      crumbs: Array,
      current: {
        type: String,
        default: ''
      },
      version: {
        type: Number,
        default: 1
      },
      preText: {
        type: [String, Function],
        default: ''
      },
      postText: {
        type: [String, Function],
        default: ''
      },
      origin: {
        type: [String],
        default: 'Admin Panel'
      }
    },
    components: {
      UivBreadcrumbs,
      UivBreadcrumbItem
    },
    data: function () {
      return {}
    },
    computed: {
      getPreText: function () {
        return this.getText(this.preText)
      },
      getPostText: function () {
        return this.getText(this.postText)
      },
      hasCrumbs: function () {
        return this.crumbs.length > 0
      }
    },
    methods: {
      getText: function (val) {
        if (typeof val === 'string') {
          return val
        } else if (typeof val === 'function') {
          var res = val()
          if (typeof res === 'string') {
            return res
          } else if (typeof res === 'object') {
            var {str, target} = res
            if (target === 'text' || target === '') {
              return str
            } else if (target === 'css') {
              return '<span style="'+ target +'">'+ str +'</span>'
            }
          }
        }
      }
    }
  }
</script>