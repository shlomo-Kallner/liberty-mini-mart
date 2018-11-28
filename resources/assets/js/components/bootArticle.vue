<template>
    <div>
        <div class="row">
            <div :class="headerCss">

                <h2 v-if="window._.size(header) > 0">
                    <span v-html="header"></span>
                </h2>
                
                <boot-figure-component v-if="window._.size(image) > 0" v-bind="image">
                </boot-figure-component>

                <h4 v-if="window._.size(subheading) > 0">
                    <span v-html="subheading"></span>
                </h4>
                
            </div>
            <div :class="articleCss" v-if="!useSepparateRow">
                <span v-html="article"></span>
            </div>
        </div>
        <div class="row" v-if="window._.size(article) > 0 && useSepparateRow">
            <div :class="articleCss">
                <span v-html="article"></span>
            </div>
        </div>
    </div>
</template>

<script>
    import {BootFigure} from './bootFigure.vue';
    export default {
        name: 'boot-article-component',
        props: {
            initHeader: String,
            initSubheading: String,
            initArticle: String,
            initHeaderCss: String,
            initArticleCss: String,
            initImage: Object,
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
        }
    }
</script>