<template>
    <div class="row margin-bottom-40">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <boot-breadcrumbs v-bind="breadcrumbs"></boot-breadcrumbs>
            <boot-article v-bind="initArticle"></boot-article>
            <boot-tabs :tabs="tabs" :current="currentTab"></boot-tabs>
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
    import { Stack } from '../../lib/LibertyStack.js'
    import { ComponentTree } from '../../lib/LaravelComponentTree.js'
    import { mapState, mapMutations, mapActions, mapGetters } from 'vuex'
    import BootBreadcrumbs from '../bootBreadcrumbs.vue'
    import BootTabs from '../bootTabs.vue'
    import BootArticle from '../bootArticle.vue'
    
    export default {
        name: 'admin-panel-component',
        props: {
            initPages: Object,
            initSections: Object,
            initUsers: Object,
            initArticle: Object
        },

        components: {
            BootBreadcrumbs,
            BootTabs,
            BootArticle
        },
        data: function () {
            var tabs = [
                {
                    value: {
                        name: 'Sections',
                        path: 'store/sections',
                        component: {
                            template: ''
                        }
                    },
                    children: []
                },
                {
                    value: {
                        name: 'Users',
                        path: 'users',
                        component: {
                            template: ''
                        }
                    },
                    children: []
                },
                {
                    value: {
                        name: 'Pages',
                        path: 'pages',
                        component: {
                            template: ''
                        }
                    },
                    children: []
                }
            ]
            this.$store.commit('setComponents', tabs)
            return {
                currentTab: this.tabs[0].value.name,
            };
        },
        watch: {
            $route: function (route) {

            }
        },
        computed: {
            ...mapState({
                breadcrumbs: state => {
                    return {
                        crumbs: state.breadcrumbs.data(),
                        current: this.$route.path
                    }
                },
                tabs: state => {
                    var i = state.getters.findComponent(this.currentTab, (tv, ct) => {
                        return ct === tv.name
                    })
                    return 
                }
            })
        },
        methods: {

        }
    }
</script>