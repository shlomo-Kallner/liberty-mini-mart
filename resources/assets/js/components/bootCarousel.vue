<template>
    <div>
        <div v-if="hasImages" :id="cId"
            :class="['carousel', 'slide']" 
            :data-ride="carousel"
        >
            <!-- Indicators -->
            <ol class="carousel-indicators">
                <li v-for="(item, idx) in images" 
                    v-bind:key="idx"
                    :data-target="'#' + cId" 
                    :data-slide-to="idx" 
                    :class="{active: window._.indexOf(images, window._.first(images)) === idx}"
                    >
                </li>
            </ol>
          
            <!-- Wrapper for slides -->
            <div class="carousel-inner" role="listbox">
                <div v-for="(item, idx) in images" 
                    v-bind:key="idx"
                    :class="['item', {active: window._.indexOf(images, window._.first(images)) === idx}]"                        
                    >
                    <img :src="item.img" :alt="item.alt">
                    <div class="carousel-caption" v-html="item.cap"></div>
                </div>
            </div>
          
            <!-- Controls -->
            <a class="left carousel-control" :href="'#' + cId" role="button" data-slide="prev">
              <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
              <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control" :href="'#' + cId" role="button" data-slide="next">
              <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
              <span class="sr-only">Next</span>
            </a>
        </div>
        <div v-else class="panel panel-default">
            <div class="panel-body">
                <h4>{{ defaultText }}</h4>
            </div>
        </div>
    </div>
</template>

<script>
    // import _ from 'lodash'
    export default {
        name: 'boot-carousel-component',
        props: {
            images: Array,
            carouselID: String,
        },
        data: function () {
            return {
                defaultText: 'We Are Sorry! We have no Images!',
                cId: 'carousel-' + this.carouselID
            };
        },
        computed: {
            hasImages: function () {
                return window._.size(this.images) > 0;
            }
        }
    }
</script>