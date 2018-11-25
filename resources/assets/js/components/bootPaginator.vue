<template>
    <!-- BEGIN PAGINATOR -->
    <div class="row">
        <div class="col-md-4 col-sm-4 items-info">
            Items {{ pageIdx.begin + 1 }} 
            to {{ pageIdx.end }} 
            of {{ totalItems }} total
        </div>

        <div class="col-md-8 col-sm-8" v-if="paging">
            <ul class="pagination pull-right">
                <li v-if="hasPrevLink">
                    <a @click="prevView" aria-label="Previous">
                        <i class="fa fa-chevron-left"></i>
                    </a>
                </li>

                <li v-for="(item, idx) in pageSlice" :key="idx">
                    <span v-if="item === thisPage">
                        {{ item + 1 }}
                    </span> 
                    <a v-else @click="goToPage(item)">
                        {{ item + 1 }}
                    </a>
                </li>
                
                <li v-if="hasNextLink">
                    <a @click="nextView" aria-label="Next">
                        <i class="fa fa-chevron-right"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <!-- END PAGINATOR -->
</template>

<script>
    // import _ from 'lodash'
    // const url = require('url');
    export default {
        name: 'bootPaginator',
        props: {
            numPages: Number,
            currentPage: Number,
            pagesPerView: Number,
            itemsPerPage: Number,
            totalItems: Number,
            // baseUrl: String,
            // pagingFor: String
        },
        data: function () {
            return {
                numViews: window.myUtils.genNumSubRanges(
                    this.numPages, this.pagesPerView
                ),
                thisPage: this.currentPage,
                currentView: this.getCurrentView(this.currentPage),
                // urlObj:  url.parse(this.baseUrl),
            };
        },
        computed: {
            hasPrevLink: function () {
                return this.numViews > 1 && this.currentView > 0;
            },
            hasNextLink: function () {
                return this.numViews > 1 
                    && this.currentView < (this.numViews - 1);
            },
            pageSlice: function () {
                var view = window.myUtils.genFirstAndLastIndex(
                    this.numPages, this.currentView, this.pagesPerView
                );
                return window._.range(view.begin, view.end - 1);
            },
            paging: function () {
                return this.numPages > 1;
            },
            pageIdx: function () {
                return window.myUtils.genFirstAndLastIndex(
                    this.totalItems, this.thisPage, this.itemsPerPage
                );
            }
        },
        methods: {
            getCurrentView: function (currentPage) {
                return window.myUtils.getCurrentSubRange(
                    currentPage, this.pagesPerView
                );
            },
            goToPage: function (pageNum) {
                this.thisPage = pageNum;
                this.$emit('paging-event', pageNum, this.currentView);
            },
            prevView: function () {
                this.currentView--;
            },
            nextView: function () {
                this.currentView++;
            }
        }
    }
</script>