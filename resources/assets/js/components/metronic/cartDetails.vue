<template>
    <div class="row margin-bottom-40">
        <!-- BEGIN CONTENT -->
        <div class="col-md-12 col-sm-12">
        <h1>Shopping cart</h1>
        <div class="goods-page">
            <div class="goods-data clearfix">
                <div class="table-wrapper-responsive">
                    <table summary="Shopping cart">
                        <tr>
                            <th class="goods-page-image">Image</th>
                            <th class="goods-page-description">Description</th>
                            <th class="goods-page-ref-no">Ref No</th>
                            <th class="goods-page-quantity">Quantity</th>
                            <th class="goods-page-price">Unit price</th>
                            <th class="goods-page-total" colspan="2">Total</th>
                        </tr>
                        <tr v-for="(item, index) in items" :key="index">
                            <td class="goods-page-image">
                                <a :href="item.url"><img :src="item.img" :alt="item.name" class="img-responsive"></a>
                                <a :href="item.url" class="btn btn-default fancybox.image fancybox-button">Zoom Image</a>
                            </td>
                            <td class="goods-page-description">
                                <h3><a :href="item.url">{{item.title}}</a></h3>
                                <p>{{item.description}}</p>
                            </td>
                            <td class="goods-page-ref-no">
                                javc2133
                            </td>
                            <td class="goods-page-quantity">
                                <div class="product-quantity">
                                    <input id="product-quantity" type="text" :value="item.quantity" readonly class="form-control input-sm">
                                </div>
                            </td>
                            <td class="goods-page-price">
                                <strong><span>$</span>47.00</strong>
                            </td>
                            <td class="goods-page-total">
                                <strong><span>$</span>47.00</strong>
                            </td>
                            <td class="del-goods-col">
                                <a class="del-goods" href="javascript:;">&nbsp;</a>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="shopping-total">
                    <ul>
                        <li>
                            <em>Sub total</em>
                            <strong class="price"><i :class="'fa ' + currency"></i>{{ subTotal }}</strong>
                        </li>
                        <li v-for="(item, index) in conditions" :key="index">
                            <em>{{ item.name }}</em>
                            <strong class="price">
                                <i v-if="! hasSymbol(item.value)" :class="'fa ' + currency"></i>{{ item.value }}
                            </strong>
                        </li>
                        <li class="shopping-total-price">
                            <em>Total</em>
                            <strong class="price"><i :class="'fa ' + currency"></i>{{ total }}</strong>
                        </li>
                    </ul>
                </div>
            </div>
            
            <a class="btn btn-default pull-left" :href="baseUrl + '/home'" role="button">Continue shopping <i class="fa fa-shopping-cart"></i></a>
            <button class="btn btn-primary pull-right" type="submit">Checkout <i class="fa fa-check"></i></button>
        </div>
        </div>
        <!-- END CONTENT -->
    </div>
</template>

<script>
    
    var _ = require('lodash');
    export default {
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
            },
            addToCart: function (id, api, quantity) {
                var info = {
                    id: id,
                    numProducts: quantity
                }
                var url = api
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
            remFromCart: function (id, api, quantity) {
                var info = {
                    id: id,
                    numProducts: quantity
                }
                var url = api
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
            hasSymbol: function (val) {
                // var reg1 = /[&+-]{1,2}/
                var reg = /%/
                return reg.test(val) 
            }
        }
    }
</script>

<style>

</style>
