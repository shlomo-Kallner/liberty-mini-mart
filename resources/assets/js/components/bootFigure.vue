<template>
    <figure class="thumbnail">
        <img class="img-responsive" :src="img" :alt="alt">
        <figcaption v-if="window._.size(cap) > 0">
            <span v-html="cap"></span>
        </figcaption>
    </figure>
</template>

<script>
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
        computed: {},
        methods: {}
    }
</script>