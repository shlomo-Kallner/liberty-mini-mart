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
            <boot-paginator v-bind="getPaging"></boot-paginator>
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
    import BootPaginator from '../lib/bootPaginator.vue'

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
                type: [Array, Function],
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
            },
            numPerPage: {
                type: Number,
                default: 8
            },
            currentPage: {
                type: Number,
                default: 1
            }
        },
        components: {
            BootPaginator
        },
        data: function () {
            return {
                itemsAreLoaded: false,
                itemsArray: this.loadItems(),
                pagingIsLoaded: false,
                paginator: this.loadPaging()
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
            hasItems: function () {
                return this.getItems.length > 0
            },
            currentPath: function () {
                return myUtils.testStr(this.path) ? this.path : this.$route.path
            },
            getItems: function () {
                return this.loadItems()
            },
            getPaging: function () {
                return this.loadPaging(this.getItems)
            },
            currentComp: function () {
                return this.$store.getters.findComponent(
                    myUtils.testStr(this.currentPath) ? this.currentPath : this.$store.route.path, 
                    (tv, p) => { 
                        // myUtils.dumpData(tv)
                        // myUtils.dumpData(path)
                        if(myUtils.testData(tv) && myUtils.testStr(p)) {
                            if (typeof tv === 'object') {
                                return tv.path === p
                            } else if (typeof tv === 'string') {
                                return tv === p
                            } else {
                                return false
                            }
                        } else {
                            return false
                        }
                    }
                )
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
            loadPaging: function (data = null) {
                var p = this.currentComp
                if (myUtils.testData(p) && myUtils.testData(p.value().pagination)) {
                    return p.value().pagination
                } else if (myUtils.testData(data)) {
                    return myUtils.genPagingData(data)
                } else {
                    return myUtils.genPagingData(null)
                }
            },
            loadItems: function () {
                if (Array.isArray(this.items) && this.items.length > 0) {
                    this.itemsAreLoaded = true
                    return this.items
                } else {
                    var c = this.currentComp
                    var i = []
                    if (myUtils.testData(c)) {
                        for (var j of c.getChildren()) {
                            var k = j.value()
                            if (myUtils.testData(k)) {
                                i.push(k)
                            }
                        }
                    }
                    // myUtils.dumpData(i)
                    if (Array.isArray(i) && i.length > 0) {
                        this.itemsAreLoaded = true
                        return i
                    } else { 
                        return []
                    }
                }
            }
        }
    }
</script>