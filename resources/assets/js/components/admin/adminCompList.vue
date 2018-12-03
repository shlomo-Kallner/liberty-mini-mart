<template>
    <div>
        <router-view></router-view>
        <div class="list-group" v-if="items.length > 0">
            <router-link :class="['list-group-item', {active: current === item.path}]" 
            v-for="(item, index) in items" :key="index"
            :to="item.path"
            >
                {{this.getPreText}} {{item.name}} {{this.getPostText}}
            </router-link>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'admin-comp-list-component',
        props: {
            itemsArray: Array,
            current: Object,
            preText: {
                type: [String, Function],
                default: ''
            },
            postText: {
                type: [String, Function],
                default: ''
            }
        },
        data: () => {
            return {
                items: itemsArray,
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
            current: () => this.$route.path
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