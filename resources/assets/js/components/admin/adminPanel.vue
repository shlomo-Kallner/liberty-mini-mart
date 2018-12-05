<template>
    <div id="cms-app" class="row margin-bottom-40">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <boot-breadcrumbs v-if="breadcrumbs.crumbs.length > 0" v-bind="breadcrumbs"></boot-breadcrumbs>
            <boot-article v-bind="initArticle"></boot-article>
            <boot-tabs v-if="false" :tabs="tabs" :current="currentTab"></boot-tabs>
            <div class="row padding-top-5">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <keep-alive>
                        <router-view></router-view>
                    </keep-alive>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue'
    import Vuex from 'vuex'
    import VueRouter from 'vue-router'

    Vue.use(VueRouter)
    Vue.use(Vuex)

    import { mapState, mapMutations, mapActions, mapGetters } from 'vuex'
    import { Stack } from '../../lib/LibertyStack.js'
    import { ComponentTree } from '../../lib/LaravelComponentTree.js'
    import BootBreadcrumbs from '../bootBreadcrumbs.vue'
    import BootTabs from '../bootTabs.vue'
    import BootArticle from '../bootArticle.vue'
    
    export default {
        name: 'admin-panel-component',
        props: {
            initArticle: {
                type: Object,
                default: () => { return {} }
            }
        },
        components: {
            BootBreadcrumbs,
            BootTabs,
            BootArticle
        },
        beforeRouteEnter (to, from, next) {
            store.commit('setCrumbs', {route: to})
            next()
        },
        beforeRouteUpdate (to, from, next) {
            this.$store.commit('setCrumbs', {route: to})
            next()
        },
        data: function () {
            this.$router.push('/')
            return {
                currentTab: ''
            }
        },
        /* watch: {
            '$route': function (to, from) {
                this.$store.commit('setCrumbs', {route: to})
            }
        }, */
        computed: {
            breadcrumbs: function () {
                return {
                    crumbs: this.getBreadcrumbs,
                    current: this.$route.path
                }
            },
            ...mapState({
                tabs: (state) => {
                    var i = state.getters.getComponentChildrenValues(
                        this.currentTab, (tv, ct) => {
                        return ct === tv.name
                    })
                    return (i.length > 0) ? i : []
                }
            }),
            ...mapGetters(['getBreadcrumbs'])
        },
        methods: {}
    }
</script>