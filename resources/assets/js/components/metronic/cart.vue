<template>
    <div class="top-cart-block">
        <div class="top-cart-info">
            <a href="javascript:void(0);" class="top-cart-info-count">
                {{ this.totalItems }} 
                {{ this.totalItemsText }}
            </a>
            <a href="javascript:void(0);" class="top-cart-info-value">
                <i :class="'fa' + this.currency"></i>
                {{ this.subTotal }}
            </a>
        </div>
        <i class="fa fa-shopping-cart"></i>

        <div class="top-cart-content-wrapper">
            <div class="top-cart-content">
                <ul class="scroller" style="height: 250px;" v-if="itemsLength > 0">
                    <li v-for="item in items" v-bind:key="item.id">
                        <a :href="item.url">
                            <img :src="item.img" :alt="item.description" width="37" height="34">
                        </a>
                        <span class="cart-content-count">
                                <i class="fa fa-times"></i>
                                {{ item.quantity }}
                        </span>
                        <strong>
                            <a :href="item.url">
                                {{ item.name }}
                            </a>
                        </strong>
                        <em>
                            <i :class="'fa' + this.currency"></i>
                            {{ item.priceSum }}
                        </em>
                        <a href="javascript:;" class="del-goods text-center"
                            :data-cart-item-id="item.id"
                            :data-cart-item-quantity="item.quantity" 
                            :data-cart-api-url="item.api + '/delfromcart'"
                            @click="delFromACart(item.id, item.api + '/delfromcart', item.quantity)">
                            <i class="fa fa-times-circle"></i>
                        </a>
                    </li>
                </ul>
                <ul class="scroller" style="height: 250px;" v-else>
                    <li>
                        Your shopping cart is empty!
                    </li>
                </ul>
                <div class="pull-right">

                    <a :href="baseUrl + '/cart'" class="btn btn-default">
                        View Cart
                    </a>
                    <a :href="baseUrl + '/checkout'" class="btn btn-primary">
                        Checkout
                    </a>

                </div>
            </div>
        </div>            
    </div>
</template>

<script>
    var _ = require('lodash');
    // var isArray = require('isarray');
    export default {
        name: 'cart-component',
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
            currency: function () {
                return this.cart.currencyIcon;
            },
            subTotal: function () {
                return this.cart.subTotal;
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
            delFromACart: function (id, api, quantity) {
                var info = {
                    id: id,
                    numProducts: quantity
                }
                var url = api
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
            }
        }

    }
</script>