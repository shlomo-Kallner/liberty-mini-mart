<template>
    <!-- BEGIN PAGINATOR -->
    <div class="row">
        <div class="col-md-4 col-sm-4 items-info">
            Items {{ firstItemIndex + 1 }} 
            to {{ lastItemIndex + 1 }} 
            of {{ totalItems }} total
        </div>

        <div class="col-md-8 col-sm-8" v-if="paging">
            <ul class="pagination pull-right">
                <li v-if="hasPrevLink">
                    <a @click="prevView" aria-label="Previous">
                        <i class="fa fa-chevron-left"></i>
                    </a>
                </li>

                <li v-for="item in pageSlice">
                    <span v-if="item === currentPage">
                        {{ item + 1 }}
                    </span> 
                    <a v-else :href="this.genUrl(item, this.currentView)">
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
    import _ from 'lodash'
    const url = require('url');
    export default {
        name: 'bootPaginator',
        props: {
            numPages: Number,
            currentPage: Number,
            pagesPerView: Number,
            firstItemIndex: Number,
            lastItemIndex: Number,
            totalItems: Number,
            baseUrl: String,
            pagingFor: String
        },
        data: function () {
            return {
                numViews: this.genNumSubRanges(
                    this.numPages, this.pagesPerView
                ),
                currentView: this.getCurrentView(),
                urlObj:  url.parse(this.baseUrl),
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
                var view = this.genFirstAndLastIndex(
                    this.numPages, this.currentView, this.pagesPerView
                );
                return _.range(view.begin, view.end - 1);
            },
            paging: function () {
                return this.numPages > 1;
            }
        },
        methods: {
            genNumSubRanges: function (numItems, itemsPerSubRange) {
                var ni = _.floor(numItems);
                var ipsr = _.floor(itemsPerSubRange);
                if (ni <= ipsr) {
                    return 1;
                } else {
                    var num = ni / ipsr;
                    return ni % ipsr > 0 ? num + 1 : num;
                } 
            },
            getCurrentView: function () {
                var current = _.floor(this.currentPage / this.pagesPerView);
                return current < this.numViews ? current : ;
            },
            genFirstAndLastIndex: function (numItems, pageNum, itemsPerPage) {
                var numPages = this.genNumSubRanges(numItems, itemsPerPage);
                if (pageNum > 0 && pageNum >= numPages) {
                    pageNum = pageNum % numPages;
                } else if (pageNum < 0) {
                    pageNum = -pageNum;
                    if (pageNum >= numPages) {
                        pageNum = pageNum % numPages;
                    } 
                    pageNum = numPages - pageNum;
                } 
                first = _.max([0, pageNum * numItemsPerPage]);
                if (first > numItems) {
                    first -= numItems;
                }
                last = first + numItemsPerPage;
                if (last >= numItems) {
                    last -= (last - numItems);  
                } 
                return {
                    begin: first,
                    end: last,
                    index: pageNum
                };
            },
            genUrl: function (pageNum, viewNum) {
                return url.format({
                    protocol: this.urlObj.protocol,
                    host: this.urlObj.host,
                    pathname: this.urlObj.pathname,
                    query: {
                        pageNum: pageNum,
                        viewNum: viewNum,
                        pagingFor: this.pagingFor
                    }
                });
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