<template>
    <div>
        
        <div v-if="_.size(items) > 0" class="panel-group" id="{{ $panelGroupId }}" role="tablist" aria-multiselectable="true">
        
            
            <boot-content-item v-for="(item, idx) in items" v-bind:key="idx"
             v-bind="item">
            </boot-content-item>
        
            <div v-if="myUtils.testData(pagingData)" class="panel panel-default">
                <div class="panel-body">
                    <boot-paginator v-bind="pagingData"></boot-paginator>
                </div>
            </div>
        
        </div>
        <div v-else class="panel-group" id="{{ $panelGroupId }}" role="tablist" aria-multiselectable="true">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h4>We Are Sorry! We have no {{ itemTypeName }}!</h4>
                </div>
            </div>
        </div>
    </div>
    
        
</template>

<script>
    import myUtils from '../../utils.js';
    import url from 'url';
    import _ from 'lodash';
    import axios from 'axios';

    import BootPaginator from '../bootPaginator.vue';
    import BootContentItem from './contentItem.vue';
    import summernote from 'summernote';

    export default {
        name: 'boot-content-panel',
        props: {
            itemTypeName: String,
            items: Array,
            paginator: Object,
            panelGroupId: String,
            asyncPager: null,
            segmentUrl: String,
            baseUrl: String,
            contentType: String
            
        },
        components: {
            BootPaginator, 
            BootContentItem
        },
        data: function () {
            return {
                pgId: this.panelGroupId + '-panel-group',
                selecttedItem: 

            }
        },
        computed: {
            pagingData: function () {
                return myUtils.getPagingData(this.paginator);
            }
        },
        methods: {
            genItemUrl: function (segmentUrl) {
                return url.resolve(this.baseUrl, '/' + segmentUrl);
            },
            genItemEditUrl: function (segmentUrl) {
                return url.resolve(this.genItemUrl(segmentUrl), '/edit');
            },
            genSiblingCreateUrl: function (segmentUrl) {
                return url.resolve(this.baseUrl, '/create');
            },
            genPId1: function (segmentUrl) {
                return 'heading' + _.capitalize(this.contentType) + 'Panel-of-' + segmentUrl;
            },
            genPId2: function (segmentUrl) {
                return 'collapse' + _.capitalize(this.contentType) + 'Panel-of-' + segmentUrl;
            },
            genPId3: function (segmentUrl) {
                return _.lowerFirst(this.contentType) + 'ContentCollapsedDiv-of-' + segmentUrl;
            },
            genPId4: function (segmentUrl) {
                return _.lowerFirst(this.contentType) + 'ImagesCollapsedDiv-of-' + segmentUrl;
            },
            
            doPaging: function (pageNum, viewNum) {
                if (this.pageAsync !== null) {
                // for now, no-op..
                } else {
                window.location.assign(myUtils.genUrl(pageNum, viewNum, this.pgId));
                }
            },
            doItemDelete: function (url) {
                axios.delete(url).then();

            }
        }
    }
</script>