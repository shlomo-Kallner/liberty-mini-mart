<template>
    <div class="row margin-bottom-40">

        <div v-if="useTabs" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <button v-for="tab in tabs" 
                v-bind:key="tab.name"
                @click="currentTab = tab"
                :class="['btn btn-primary', { active: currentTab.name === tab.name }]" 
                :type="button"
            >
                Display {{ tab.name }}
            </button>
            <hr>
            <keep-alive>
                <component :is="currentTab.component"></component>
            </keep-alive>
        </div>
        <div v-else class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row padding-top-5">
                <ol class="breadcrumb pull-left">
                    <li :class="{active: breadcrumbStack.empty()}"><a href="#">Admin Panel</a></li>
                    <li v-for="(item, index) in breadcrumbStack.data()" 
                        :key="index"
                        :class="{active: currentTab.value().name === item.value().name}"
                    >
                        <a @click="doPaging(item, index)">{{item.value().name}}</a>
                    </li>
                </ol>
            </div>
            <div class="row padding-top-5">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <keep-alive>
                        <component :is="currentTab.value().component"></component>
                    </keep-alive>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { Stack } from '../../lib/LibertyStack.js';
    import { ComponentTree } from "../../lib/LaravelComponentTree.js";
    export default {
        name: 'admin-panel-component',
        props: {
            initPages: Object,
            initSections: Object,
            initUsers: Object
        },
        data: function () {
            return {
                useTabs: false,
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
                breadcrumbStack: new Stack([]),
            };
        },
        computed: {},
        methods: {

        }
    }
</script>