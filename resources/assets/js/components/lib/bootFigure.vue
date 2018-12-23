<template>
    <figure class="thumbnail">
        <img class="img-responsive" :src="img" :alt="alt">
        <figcaption v-if="hasCap">
            <span v-html="cap"></span>
        </figcaption>
    </figure>
</template>

<script>
    import _ from 'lodash'
    export default {
        name: 'boot-figure-component',
        props: {
            initImage: String,
            initAlt: String,
            initCaption: String
        },
        data: function () {
            return {
                image: this.initImage,
                alt: this.initAlt,
                cap: this.initCaption
            }
        },
        computed: {
            hasCap: () => _.size(this.cap) > 0
        },
        methods: {}
    }
</script>