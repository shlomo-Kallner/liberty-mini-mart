<template>
  <div id="cms-app">
    <div class="row margin-bottom-40">
      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
          <router-link :to="backPath"><i class="fa fa-arrow-left" aria-hidden="true"></i></router-link>
          <boot-breadcrumbs v-if="breadcrumbs.crumbs.length > 0" v-bind="breadcrumbs"></boot-breadcrumbs>
          <!-- <boot-article v-bind="initArticle"></boot-article> -->
          <div class="row padding-top-5">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <keep-alive>
                <boot-loader v-if="isLoading"></boot-loader>
                <router-view v-else></router-view>
              </keep-alive>
            </div>
          </div>
      </div>
    </div>
  </div>
</template>

<script>
    import Vue from 'vue'
    // import VueAtlas from 'vue-atlas'
    import Vuex from 'vuex'
    import VueRouter from 'vue-router'
    
    Vue.use(VueRouter)
    Vue.use(Vuex)
    // vue.use(VueAtlas, 'en')

    import { mapState, mapMutations, mapActions, mapGetters } from 'vuex'
    import { Stack } from '../../lib/LibertyStack.js'
    import { ComponentTree } from '../../lib/LaravelComponentTree.js'
    import BootBreadcrumbs from '../lib/bootBreadcrumbs.vue'
    // import BootTabs from '../lib/bootTabs.vue'
    // import BootArticle from '../lib/bootArticle.vue'
    import BootLoader from '../lib/bootLoader.vue'
    
    export default {
        name: 'admin-panel-component',
        props: {
           /*  initArticle: {
                type: Object,
                default: () => { return {} }
            } */
        },
        components: {
            BootBreadcrumbs,
            // BootTabs,
            BootArticle,
            BootLoader
        },
        beforeRouteEnter (to, from, next) {
            store.commit('setCrumbs', {route: to})
            next()
        },
        beforeRouteUpdate (to, from, next) {
            this.$store.commit('setCrumbs', {route: to})
            this.backPath = from.path
            next()
        },
        data: function () {
            this.$router.push('/')
            return {
                // currentTab: '/',
                backPath: '',
                isLoading: true
            }
        },
        computed: {
            breadcrumbs: function () {
                return {
                    crumbs: this.getBreadcrumbs,
                    current: this.$route.path
                }
            },
            ...mapState({
                /* tabs: (state) => {
                    var i = state.getters.getComponentChildrenValues(
                        this.currentTab, (tv, ct) => {
                        return ct === tv.path
                    })
                    return (i.length > 0) ? i : []
                } */
            }),
            ...mapGetters(['getBreadcrumbs'])
        },
        methods: {}
    }
</script>