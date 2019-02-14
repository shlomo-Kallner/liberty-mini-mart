<template>
    <tr>
        <td class="goods-page-image">
            <a :href="item.img" class="btn btn-default fancybox.image fancybox-button">
                <img :src="item.img" :alt="item.name" class="img-responsive">
            </a>
        </td>
        <td class="goods-page-description">
            <btn type="link" @click="modal_show = true">
                {{item.name}}
            </btn>
            <modal v-model="modal_show" size="lg" :title="item.name" :footer="false">
                <template slot="header">
                    <h3><a :href="item.url">{{item.name}}</a></h3>      
                </template>
                <p>{{item.description}}</p>
                <div v-if="!isEmpty(item.conditions)">
                    <tooltip v-for="(cond, n) in item.conditions" :key="n" 
                        :text="cond.description" :enable="!isEmpty(cond.description)">
                        <p>
                            {{ cond.name }} : 
                            <i :class="'fa ' + currency"></i>{{ cond.calcValue }}
                        </p>
                    </tooltip>
                </div>
            </modal>
        </td>
        <!-- 
            <td class="goods-page-ref-no">
                javc2133
            </td>
        -->
        <td class="goods-page-quantity">
            <div class="product-quantity">
                <boot-touchspin 
                    v-bind:value="toInteger(item.quantity)"
                    @update:value="changeQuantiy(item, toInteger($event))"
                    >
                </boot-touchspin>
            </div>
        </td>
        <td class="goods-page-price">
            <strong>
                <i :class="'fa' + currency"></i>
                {{ toFoat(item.price) }}
            </strong>
        </td>
        <td class="goods-page-price">
            <strong>
                <i :class="'fa' + currency"></i>
                {{ toFoat(item.priceCalc) }}
            </strong>
        </td>
        <td class="goods-page-total">
            <strong>
                <i :class="'fa' + currency"></i>
                {{ toFoat(item.priceSum) }}
            </strong>
        </td>
        <td class="del-goods-col">
            <a href="javascript:;" class="del-goods text-center"
                @click="delFromCart(item)">
                <i class="fa fa-times-circle"></i>
            </a>
        </td>
    </tr>
</template>

<script>
  import BootTouchspin from '../lib/bootTouchspin.vue'
  import {Tooltip, Modal, Btn} from 'uiv'
  import _ from 'lodash'
    
  export default {
        components: {
            BootTouchspin,
            Tooltip, 
            Modal, 
            Btn
        },
        props: {
            value: Object,
            baseUrl: String,
            currency: String,
            debug: {
                type: Boolean,
                default: false
            }
        },
        data: function () {
            return {
                item: this.value,
                modal_show: false
            }
        },
        watch: {
            value : function (newVal, oldVal) {
                this.item = newVal
                //this.$forceUpdate()
            }
        },
        computed: {
            doAjax: function () {
                var _this = this
                return _.debounce( 
                    function (item, url, quantity, action, debug = false) {
                        var info = {
                            id: item.id,
                            numProducts: quantity
                        }
                        var url = item.api + url
                        var data = window.Laravel.handleCart.makeData(
                            info, url, window.Laravel.csrfToken,
                            '', action, window.Laravel.nut
                        )
                        var callback = function (result) {
                            window.Laravel.page.setGoods(result)
                            //_this.$forceUpdate()
                        }
                        //
                        window.Laravel.handleCart.doAjax(
                            window.jQuery, data, 'POST', callback, debug
                        )
                    }, 
                    500, {trailing: true})
            }
        },
        methods: {
            delFromCart: function (item) {
                var dbg = false
                this.doAjax(
                    item, '/delfromcart', item.quantity, 
                    'delFromCart', dbg 
                )
            },
            addToCart: function (item, quantity) {
                var dbg = false
                this.doAjax(
                    item, '/addtocart', quantity, 
                    'addToCart', dbg
                )
            },
            remFromCart: function (item, quantity) {
                var dbg = this.debug
                this.doAjax(
                    item, '/remfromcart', quantity, 
                    'remFromCart', dbg 
                )
            },
            changeQuantiy: function (item, quantity) {
                if (_.isNumber(quantity) && _.isInteger(quantity)) {
                    if (item.quantity != quantity && quantity > 0) {
                        if (item.quantity > quantity) {
                            var diff = item.quantity - quantity
                            this.remFromCart(item, diff)
                        } else if (item.quantity < quantity) {
                            var diff = quantity - item.quantity
                            this.addToCart(item, diff)
                        }
                    } else if (quantity <= 0) {
                        //this.delFromCart(item)
                        this.changeQuantiy(item, 1)
                    }
                }
            },
            isEmpty: function (data) {
                return _.isEmpty(data)
            },
            toInteger: function (val) {
                return _.toInteger(val)
            },
            toFoat: function (val) {
                var v = _.toFinite(val)
                return _.floor(v, 2)
            }
        }
  }
</script>
