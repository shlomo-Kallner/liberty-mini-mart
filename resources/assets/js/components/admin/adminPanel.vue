<template>
    <div id="cms-app" class="row margin-bottom-40">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <boot-breadcrumbs v-bind="breadcrumbs"></boot-breadcrumbs>
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
    import { Stack } from '../../lib/LibertyStack.js'
    import { ComponentTree } from '../../lib/LaravelComponentTree.js'
    import { mapState, mapMutations, mapActions, mapGetters } from 'vuex'
    import BootBreadcrumbs from '../bootBreadcrumbs.vue'
    import BootTabs from '../bootTabs.vue'
    import BootArticle from '../bootArticle.vue'

    const Foo = { template: '<div>Hello World!</div>' }
    
    export default {
        name: 'admin-panel-component',
        props: {
            initPages: {
                type: Object,
                default: {}
            },
            initSections: {
                type: Object,
                default: {}
            },
            initUsers: {
                type: Object,
                default: {}
            },
            initArticle: {
                type: Object,
                default: {}
            }
        },
        components: {
            BootBreadcrumbs,
            BootTabs,
            BootArticle
        },
        data: function () {
            return this.genTabs()
        },
        watch: {
            '$route': function (to, from) {
                this.$store.commit('setCrumbs', {to})
            }
        },
        computed: {
            breadcrumbs: () => {
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
        methods: {
            valToComponent: (data) => {
                if (typeof data === 'object') {
                    return {
                        path: data.url,
                        component: Foo,
                        paging: {},
                        ...data
                    }
                } else {
                    return null
                }
            },
            valToComponentArray: (data) => {
                if (Array.isArray(data)) {
                    var res = []
                    for (var i in data) {
                        if (i instanceof ComponentTree) {
                            res.push(i)
                        } else {
                            res.push(new ComponentTree(this.valToComponent(i)))
                        } 
                    }
                    return res
                } else {
                    return []
                }
            },
            genTabs: () => {
                var tabs = [
                    {
                        value: {
                            name: 'Sections',
                            path: 'store/sections',
                            component: Foo,
                            paging: window.myUtils.getPagingFrom(this.initSections)
                        },
                        children: this.valToComponentArray(window.myUtils.getItemsFrom(this.initSections))
                    },
                    {
                        value: {
                            name: 'Users',
                            path: 'users',
                            component: Foo,
                            pagination: window.myUtils.getPagingFrom(this.initUsers)
                        },
                        children: this.valToComponentArray(window.myUtils.getItemsFrom(this.initUsers))
                    },
                    {
                        value: {
                            name: 'Pages',
                            path: 'pages',
                            component: Foo,
                            pagination: window.myUtils.getPagingFrom(this.initPages)
                        },
                        children: this.valToComponentArray(window.myUtils.getItemsFrom(this.initPages))
                    }
                ]
                this.$store.commit('setComponents', tabs)
                return {
                    currentTab: '',
                };
            }
        }
    }
</script>