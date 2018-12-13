<template>
    <div class="row padding-top-5">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <router-link v-for="(item, index) in tabbuttons" 
                v-bind:key="index"
                :class="['btn btn-primary', { active: current === item.name }]" 
                :to="item.path"
            >
                {{this.getPreText}} {{item.name}} {{this.getPostText}}
            </router-link>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'boot-tabs-component',
        props: {
            tabs: Array,
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
                tabbuttons: tabs
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
        watch: {
            tabs: function (crumb) {
                this.tabbuttons = crumb
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
            }
        }
    }
</script>