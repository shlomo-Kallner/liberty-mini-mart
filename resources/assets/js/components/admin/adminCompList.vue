<template>
    <div>
        <router-view></router-view>
        <div class="list-group" v-if="hasItems">
            <router-link v-for="(item, index) in getItems" :key="index"
            :class="['list-group-item', {active: currentPath === item.path}]" 
            :to="item.path"
            >
                {{this.getPreText}} {{item.name}} {{this.getPostText}}
            </router-link>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue'
    import Vuex from 'vuex'
    import { mapGetters } from 'vuex';
    import VueRouter from 'vue-router'
    import _ from 'lodash'
    import myUtils from '../../utils.js'

    Vue.use(VueRouter)
    Vue.use(Vuex)

    export default {
        name: 'admin-comp-list-component',
        props: {
            path: {
                type: [Object, String],
                default: ''
            },
            items: {
                type: [Array],
                default: () => {return []}
            },
            current: {
                type: [Object, String],
                default: ''
            },
            preText: {
                type: [String, Function],
                default: ''
            },
            postText: {
                type: [String, Function],
                default: ''
            },
            extra: {
                type: Object,
                default: () => null
            }
        },
        data: function () {
            return {
                itemsArray: this.loadItems()
            }
        },
        watch: {},
        computed: {
            getPreText: function () {
                return this.getText(this.preText)
            },
            getPostText: function () {
                return this.getText(this.postText)
            },
            hasItems: function () {return this.getItems.length > 0},
            currentPath: function () {return this.$route.path},
            getItems: function () {
                return this.loadItems()
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
            },
            getValues: function (value, comp = null) { 
                return this.$store.getters.getComponentChildrenValues(value, comp)
            },
            loadItems: function () {
                var i = this.getValues(
                    typeof this.path === 'string' ? this.path : this.path.path, 
                    (tv, path) => { 
                        // myUtils.dumpData(tv)
                        // myUtils.dumpData(path)
                        if(myUtils.testData(tv) && myUtils.testData(path)) {
                            if (typeof tv === 'object') {
                                return tv.path === path 
                            } else if (typeof tv === 'string') {
                                return tv === path
                            } else {
                                return false
                            }
                        } else {
                            return false
                        }
                    }
                )
                // myUtils.dumpData(i)
                if (Array.isArray(i) && i.length > 0) {
                    return i
                } else if (this.items.length > 0) {
                    return this.items
                } else { 
                    return []
                }
            }
        }
    }
</script>