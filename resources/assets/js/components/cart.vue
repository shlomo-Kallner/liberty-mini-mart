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
                <ul class="scroller" style="height: 250px;" v-if="this.itemsLength > 0">
                    <li v-for="item in this.items" v-bind:key="item.id">
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
                        <a href="javascript:void(0);" class="del-goods text-center"
                            :data-cart-item-id="item.id" :data-cart-api-url="item.url + '/delFromCart'">
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

                    <a :href="this.baseUrl + '/cart'" class="btn btn-default">
                        View Cart
                    </a>
                    <a :href="this.baseUrl + '/checkout'" class="btn btn-primary">
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
        }

    }
</script>