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
                    <li><a href="#">Admin Panel</a></li>
                    <li><a href="#"></a></li>
                    <li class="active">{{currentTab.name}}</li>
                </ol>
            </div>
            <hr>
            <keep-alive>
                <component :is="currentTab.component"></component>
            </keep-alive>
        </div>
    </div>
</template>

<script>
    import Stack from '../../lib/LibertyStack.js';
    export default {
        name: 'admin-panel-component',
        props: {

        },
        data: function () {
            return {
                useTabs: true,
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
                breadcrumbStack: new Stack([this.currentTab.name]),
            };
        },
        computed: {},
        methods: {}
    }
</script>