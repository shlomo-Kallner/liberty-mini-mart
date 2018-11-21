<template>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <button v-for="tab in tabs" 
                v-bind:key="tab.name"
                @click="currentTab = tab"
                :class="['btn btn-primary', { active: currentTab.name === tab.name }]" 
                :type="button"
            >
                Display {{ tab.name }}
            </button>
        </div>
        <hr>
        <ol class="breadcrumb">
            <li><a href="#">Admin Panel</a></li>
            <li><a href="#"></a></li>
            <li class="active">{{currentTab.name}}</li>
        </ol>
        
        <hr>
        
        <keep-alive>
            <component :is="currentTab.component"></component>
        </keep-alive>
    </div>
</template>

<script>
    import Stack from '../../lib/LibertyStack.ts';
    export default {
        name: 'admin-panel-component',
        props: {

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
                breadcrumbStack: new Stack([this.currentTab.name]),
            };
        },
        computed: {},
        methods: {}
    }
</script>