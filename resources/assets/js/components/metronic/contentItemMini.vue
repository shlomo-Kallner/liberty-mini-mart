<template>
  <div :class="extraOuterCss">
    <div class="product-item">
      <div class="pi-img-wrapper">
        <img :src="img.img" class="img-responsive" :alt="img.alt">
        <div>
          <uiv-btn type="default" :href="img.img" class="fancybox.image fancybox-button">Zoom</uiv-btn>
          <uiv-btn type="default" @click="showPopUp = true">View</uiv-btn>
          <uiv-modal v-model="showPopUp" size="lg">
            <div class="product-page product-pop-up">
              <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-3">
                  <div class="product-main-image">
                    <img :src="img.img" :alt="title" class="img-responsive">
                  </div>
                  <div class="product-other-images" v-if="!myUtils.isEmpty(otherImages)">
                    <a v-for="(item, idx) in otherImages" :key="idx" href="javascript:;">
                      <img :alt="item.alt" :src="item.img">
                    </a>
                  </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-9">
                  <h2>{{ title }}</h2>
                  <div class="price-availability-block clearfix">
                    <div v-if="!myUtils.isEmpty(price)" class="price">
                      <strong>
                        <span v-if="myUtils.isFiniteOrConvertable(price)">
                          <i :class="['fa', currency ]"></i>
                        </span>{{ price }}
                      </strong>
                      <em v-if="!myUtils.isEmpty(sale)">
                        <i v-if="myUtils.isFiniteOrConvertable(sale)" :class="['fa', currency ]"></i>
                        <span>{{ sale }}</span>
                      </em>
                    </div>
                    <div v-if="false" class="availability">
                      Availability: <strong>In Stock</strong>
                    </div>
                  </div>
                  <div class="description">
                    <slot name="description"></slot>
                  </div>
                  <div class="product-page-options">
                    <slot name="modal-options"></slot>
                  </div>
                  <div class="product-page-cart">
                    <slot name="modal-buttons"></slot>
                  </div>
                  <uiv-btn type="info" :to="path">More details</uiv-btn>
                </div>
                <div v-if="!myUtils.isEmpty(sticker)" :class="['sticker', sticker]"></div>
              </div>
            </div>
          </uiv-modal>
        </div>
      </div>
      <h3><uiv-btn type="link" :to="path"></uiv-btn></h3>
      <div v-if="price && currency" class="pi-price">
          <i class="fa {{ currency }}"></i>
          <span>{{ price }}</span>
      </div>
      <slot name="buttons"></slot>
      <div v-if="_.size(sticker) > 0" class="sticker {{ sticker }}"></div>
    </div>
  </div>
</template>

<script>

  // import BootCarousel from '../lib/bootCarousel.vue';
  // import jquery from 'jquery';
  // import bs3 from 'bootstrap-sass';
  import _ from 'lodash';
  import myUtils from '../../utils.js';
  // import url from 'url';
  // import axios from 'axios';
  import { Modal as UivModal, Btn as UivBtn } from 'uiv'
  
  export default {
      name: 'boot-content-mini-item',
      props: {
          // panelGroupId: String,
          // segmentUrl: String,
          path: String,
          name: String,
          img: Object,
          title: String,
          otherImages: {
            type: Array,
            default: () => []
          },
          price: {
            type: [Number, String],
            default: 0
          }, 
          sale: {
            type: [Number, String],
            default: 0
          }, 
          currency: {
            type: String,
            default: 'fa-usd'
          },
          extraOuterCss: {
            type: String,
            default: ''
          },
          sticker: {
            type: String,
            default: ''
          }
      },
      components: {
          // BootCarousel,
          UivModal,
          UivBtn
      },
      data: function () {
          return {
              // items: myUtils.doOnArray([], this.getItem),
              showPopUp: false
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
              //axios.delete()
              return undefined
          }
      }
  }
</script>