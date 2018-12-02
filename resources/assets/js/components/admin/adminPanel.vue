<template>
    <div class="row margin-bottom-40">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <boot-breadcrumbs v-bind="breadcrumbs"></boot-breadcrumbs>
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
    import { ComponentTree } from "../../lib/LaravelComponentTree.js"
    import { mapState } from 'vuex'
    import BootBreadcrumbs from "../bootBreadcrumbs.vue"
    export default {
        name: 'admin-panel-component',
        props: {
            initPages: Object,
            initSections: Object,
            initUsers: Object
        },

        components: {
            BootBreadcrumbs
        },
        data: function () {
            return {
                tabs: [
                        {
                            name: 'Sections',
                            component: {
                                template: ''
                            }
                        },
                        {
                            name: 'Users',
                            component: {
                                template: ''
                            }
                        },
                        {
                            name: 'Pages',
                            component: {
                                template: ''
                            }
                        }
                    ],
                currentTab: this.tabs[0],
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
                }
            })
        },
        methods: {

        }
    }
</script>