<template>
            
    <div class="goods-data clearfix">
        <div class="table-wrapper-responsive">
            <table v-if="!_.isEmpty(items)" summary="Shopping cart">
                <tr>
                    <th class="goods-page-image">Image</th>
                    <th class="goods-page-description">Description</th>
                    <!-- 
                        <th class="goods-page-ref-no">Ref No</th>
                    -->
                    <th class="goods-page-quantity">Quantity</th>
                    <th class="goods-page-price">Unit base price</th>
                    <th class="goods-page-price">Unit price after discounts</th>
                    <th class="goods-page-total" colspan="2">Total</th>
                </tr>
                <tr v-for="(item, index) in items" :key="index">
                    <td class="goods-page-image">
                        <a :href="item.img" class="btn btn-default fancybox.image fancybox-button">
                            <img :src="item.img" :alt="item.name" class="img-responsive">
                        </a>
                    </td>
                    <td class="goods-page-description">
                        <h3><a :href="item.url">{{item.title}}</a></h3>
                        <p>{{item.description}}</p>
                        <div v-if="!_.isEmpty(item.conditions)">
                            <tooltip v-for="(cond, n) in item.conditions" :key="n" 
                                :text="cond.description" :enable="!_.isEmpty(cond.description)">
                                <p>
                                    {{ item.name }} : 
                                    <i :class="'fa ' + currency"></i>{{ cond.calcValue }}
                                </p>
                            </tooltip>
                        </div>
                    </td>
                    <!-- 
                        <td class="goods-page-ref-no">
                            javc2133
                        </td>
                    -->
                    <td class="goods-page-quantity">
                        <div class="product-quantity">
                            <boot-touchspin 
                                v-bind:value="item.quantity"
                                @update:value="changeQuantiy(item, $event)"
                                >
                            </boot-touchspin>
                        </div>
                    </td>
                    <td class="goods-page-price">
                        <strong>
                            <i :class="'fa' + this.currency"></i>
                            {{ item.price }}
                        </strong>
                    </td>
                    <td class="goods-page-price">
                        <strong>
                            <i :class="'fa' + this.currency"></i>
                            {{ item.priceCalc }}
                        </strong>
                    </td>
                    <td class="goods-page-total">
                        <strong>
                            <i :class="'fa' + this.currency"></i>
                            {{ item.priceSum }}
                        </strong>
                    </td>
                    <td class="del-goods-col">
                        <a href="javascript:;" class="del-goods text-center"
                            @click="delFromCart(item)">
                            <i class="fa fa-times-circle"></i>
                        </a>
                    </td>
                </tr>
            </table>
            
            <div v-else class="well well-lg">
                <h3 class="text-center">We are Sorry! There are No Items to display!</h3>
            </div>
        </div>

        <div class="shopping-total">
            <ul>
                <li>
                    <em>Sub total</em>
                    <strong class="price"><i :class="'fa ' + currency"></i>{{ subTotal }}</strong>
                </li>
                <li v-for="(item, index) in conditions" :key="index">
                    <tooltip :text="item.description" :enable="!_.isEmpty(item.description)">
                        <em>{{ item.name }}</em>
                        <strong class="price">
                            <i :class="'fa ' + currency"></i>{{ item.calcValue }}
                        </strong>
                    </tooltip>
                </li>
                <li class="shopping-total-price">
                    <em>Total</em>
                    <strong class="price"><i :class="'fa ' + currency"></i>{{ total }}</strong>
                </li>
            </ul>
        </div>
    </div>
    
</template>

<script>
    import BootTouchspin from '../lib/bootTouchspin.vue'
    import {Tooltip} from 'uiv'
    import _ from 'lodash'
    export default {
        components: {
            BootTouchspin,
            Tooltip
        },
        name: 'cart-page-component',
        props: ['initCart', 'baseUrl'],
        data: function () {
            return {
                cart: this.initCart
            }
        },
        watch: {
            initCart : function (newCart, oldCart) {
                this.cart = newCart;
            }
        },
        computed: {
            items: function () {
                return this.cart.items;
            },
            itemsLength: function () {
                return _.size(this.cart.items);
            },
            conditions: function () {
                return this.cart.conditions;
            },
            currency: function () {
                return this.cart.currencyIcon;
            },
            subTotal: function () {
                return this.cart.subTotal;
            },
            total: function () {
                return this.cart.total;
            },
            totalItems: function () {
                return this.cart.totalItems;
            },
            totalItemsText: function () {
                return this.totalItems == 0 || this.totalItems > 1 
                    ? 'items'
                    : 'item';
            }
        },
        methods: {
            delFromCart: function (item) {
                var info = {
                    id: item.id,
                    numProducts: item.quantity
                }
                var url = item.api + '/delfromcart'
                var data = window.Laravel.handleCart.makeData(
                    info, url, window.Laravel.csrfToken,
                    '', 'delFromCart', window.Laravel.nut
                )
                var callback = function (result) {
                    window.Laravel.page.setCart(result)
                }
                //
                window.Laravel.handleCart.doAjax(
                    window.jQuery, data, 'POST', callback
                )
            },
            addToCart: function (item, quantity) {
                var info = {
                    id: item.id,
                    numProducts: quantity
                }
                var url = item.api + '/addtocart'
                var data = window.Laravel.handleCart.makeData(
                    info, url, window.Laravel.csrfToken,
                    '', 'addToCart', window.Laravel.nut
                )
                var callback = function (result) {
                    window.Laravel.page.setCart(result)
                }
                //
                window.Laravel.handleCart.doAjax(
                    window.jQuery, data, 'POST', callback
                )
            },
            remFromCart: function (item, quantity) {
                var info = {
                    id: item.id,
                    numProducts: quantity
                }
                var url = item.api + '/remfromcart'
                var data = window.Laravel.handleCart.makeData(
                    info, url, window.Laravel.csrfToken,
                    '', 'remFromCart', window.Laravel.nut
                )
                var callback = function (result) {
                    window.Laravel.page.setCart(result)
                }
                //
                window.Laravel.handleCart.doAjax(
                    window.jQuery, data, 'POST', callback
                )
            },
            changeQuantiy: function (item, quantity) {
                if (_.isNumber(quantity) && _.isInteger(quantity)) {
                    if (item.quantity != quantity) {
                        if (item.quantity > quantity) {
                            var diff = item.quantity - quantity
                            this.remFromCart(item, diff)
                        } else if (item.quantity < quantity) {
                            var diff = quantity - item.quantity
                            this.addToCart(item, diff)
                        }
                    }
                }
            },
            hasSymbol: function (val) {
                // var reg1 = /[&+-]{1,2}/
                var reg = /%/
                return reg.test(val) 
            }
        }
    }
</script>
