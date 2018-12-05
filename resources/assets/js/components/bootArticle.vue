<template>
    <div>
        <div class="row">
            <div :class="headerCss">

                <h2 v-if="hasHeader">
                    <span v-html="header"></span>
                </h2>
                
                <boot-figure-component v-if="hasImage" v-bind="image">
                </boot-figure-component>

                <h4 v-if="hasSubheading">
                    <span v-html="subheading"></span>
                </h4>
                
            </div>
            <div :class="articleCss" v-if="!useSepparateRow">
                <span v-html="article"></span>
            </div>
        </div>
        <div class="row" v-if="hasArticle && useSepparateRow">
            <div :class="articleCss">
                <span v-html="article"></span>
            </div>
        </div>
    </div>
</template>

<script>
    import _ from 'lodash'
    import {BootFigure} from './bootFigure.vue';
    export default {
        name: 'boot-article-component',
        props: {
            initHeader: {
                type: String,
                default: ''
            },
            initSubheading: {
                type: String,
                default: ''
            },
            initArticle: {
                type: String,
                default: ''
            },
            initHeaderCss: {
                type: String,
                default: ''
            },
            initArticleCss: {
                type: String,
                default: ''
            },
            initImage: {
                type: Object,
                default: () => {return {}}
            },
            initUseSepparateRow: {
                type: Boolean,
                default: false
            }
        },
        components: {
            BootFigure
        },
        data: function () {
            return {
                headerCss: this.initHeaderCss,
                articleCss: this.initArticleCss,
                header: this.initHeader,
                subheading: this.initSubheading,
                article: this.initArticle,
                image: this.initImage,
                useSepparateRow: this.initUseSepparateRow
            }
        },
        computed: {
            hasHeader: () => _.size(this.header) > 0,
            hasImage: () =>  _.size(this.image) > 0,
            hasSubheading: () => _.size(this.subheading) > 0,
            hasArticle: () => _.size(this.article) > 0
        }
    }
</script>