<template>
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="{{ panelId1 }}">
            <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="{{'#' + panelGroupId}}" href="{{'#' + panelId2}}" aria-expanded="true" aria-controls="{{panelId2}}">
                    {{ name }}
                </a>
            </h4>
        </div>
        <div id="{{panelId2}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="{{ panelId1 }}">
            <div class="panel-body">
                <div class="row">
                    
                    <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 thumbnail">
                        <img src="{{ img.img }}" class="img-responsive" alt="{{ img.alt }}">
                    </div>
                            
                    <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
                        <div class="row">
                            <h4 v-html="title"></h4>
                        </div>
                                
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="btn-group pull-left">
                                    <a class="btn btn-default" data-toggle="collapse" href="{{'#' + panelId3}}" aria-expanded="false" aria-controls="{{ panelId3 }}" role="button">
                                        Show All Images
                                    </a>
                                    <a class="btn btn-primary" href="{{ $newCategoryCreateUrl }}" role="button">
                                        Create a New {{ _.capitalize(this.containedType) }}
                                    </a>
                                    <a class="btn btn-warning" href="{{ itemEditUrl }}" role="button">
                                        Edit {{ false ? 'this ' + _.capitalize(this.contentType) : '' }}
                                    </a>
                                    <button type="button" class="btn btn-danger" @click="doDelete(itemUrl)">
                                        Delete {{ true ? 'this ' + _.capitalize(this.contentType) : '' }}
                                    </button>
                                </div>
                                
                                <div class="btn-group pull-right">
                                    <button type="button" class="btn btn-default">{{ !$section['visible'] ? 'Show' : 'Hide' }}</button>
                                    <button type="button" class="btn btn-default">Move Up</button>
                                    <button type="button" class="btn btn-default">Move Down</button>
                                </div>
                                
                            </div>
                        </div>

                        <div class="row collapse" id="{{ panelId3 }}">
                            <boot-carousel v-bind:images="otherImages"
                                v-bind:carouselID="genPId4(segmentUrl)"
                            ></boot-carousel>
                        </div>
        
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
/* 
    import BootCarousel from '../bootCarousel.vue';
    import jquery from 'jquery';
    import bs3 from 'bootstrap-sass';
    import _ from 'lodash';
    import myUtils from '../../utils.js';
    import url from 'url';
    import axios from 'axios';
     */
    export default {
        name: 'boot-content-item',
        props: {
            panelGroupId: String,
            segmentUrl: String,
            baseUrl: String,
            name: String,
            img: Object,
            title: String,
            content: Array,
            contentType: String,
            otherImages: Array,
            containedType: String
        },
        components: {
            BootCarousel
        },
        data: function () {
            return {
                items: myUtils.doOnArray([], this.getItem)
            };
        },
        computed: {
            itemUrl: function () {
                return url.resolve(this.baseUrl, '/' + this.segmentUrl);
            },
            itemEditUrl: function () {
                return url.resolve(this.genItemUrl(this.segmentUrl), '/edit');
            },
            siblingCreateUrl: function () {
                return url.resolve(this.baseUrl, '/create');
            },
            panelId1: function () { 
                return 'heading' + _.capitalize(this.contentType) + 'Panel-of-' + segmentUrl; 
            },
            panelId2: function () {
                return 'collapse' + _.capitalize(this.contentType) + 'Panel-of-' + segmentUrl;
            },
            panelId3: function () {
                return _.lowerFirst(this.contentType) + 'ImagesCollapsedDiv-of-' + segmentUrl;
            },
            panelId4: function () {
                return _.lowerFirst(this.contentType) + 'ContentCollapsedDiv-of-' + segmentUrl;
            }
        },
        methods: {
            getItem: function (data) {
                return data;
            },
            doDelete: function (url) {
                axios.delete()
                return
            }
        }
    }
</script>