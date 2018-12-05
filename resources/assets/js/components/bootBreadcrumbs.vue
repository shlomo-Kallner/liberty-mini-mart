<template>
    <div class="row padding-top-5">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <ol class="breadcrumb pull-left">
                <li :class="{active: breadcrumbs.length === 0}">
                    <router-link to="/"></router-link>
                </li>
                <li v-for="(item, index) in breadcrumbs" 
                    :key="index"
                    :class="{active: current === item.name}"
                >
                    <router-link :to="item.path">
                        {{this.getPreText}} {{item.name}} {{this.getPostText}}
                    </router-link>
                </li>
            </ol>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue'
    import Vuex from 'vuex'
    import VueRouter from 'vue-router'

    Vue.use(VueRouter)
    Vue.use(Vuex)

    export default {
        name: 'boot-breadcrumbs-component',
        props: {
            crumbs: Array,
            current: String,
            preText: {
                type: [String, Function],
                default: ''
            },
            postText: {
                type: [String, Function],
                default: ''
            }
        },
        data: function () {
            return {
                breadcrumbs: crumbs
            }
        },
        watch: {
            crumbs: function (crumb) {
                this.breadcrumbs = crumb
            }
        },
        computed: {
            getPreText: function () {
                return this.getText(this.preText)
            },
            getPostText: function () {
                return this.getText(this.postText)
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
            page: function (val, index) {
                this.current = val.name
                if (index > 1) {
                    this.$router.push()
                } else {
                    this.$router.replace()
                }
            }
        }
    }
</script>