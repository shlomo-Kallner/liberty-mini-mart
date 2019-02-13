<template>
            
    <div class="goods-data clearfix">
        <div class="table-wrapper-responsive">
            <modal v-model="modal.show" size="lg" :title="modal.title" :footer="false">
                <template v-slot:header>
                    <h3><a :href="modal.url">{{modal.title}}</a></h3>      
                </template>
                <p>{{modal.description}}</p>
                <div v-if="!isEmpty(modal.conditions)">
                    <tooltip v-for="(cond, n) in modal.conditions" :key="n" 
                        :text="cond.description" :enable="!isEmpty(cond.description)">
                        <p>
                            {{ cond.name }} : 
                            <i :class="'fa ' + currency"></i>{{ cond.calcValue }}
                        </p>
                    </tooltip>
                </div>
            </modal>
            <table v-if="!isEmpty(items)" summary="Shopping cart">
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
                        <btn type="link" @click="showModal(item)">
                            {{item.name}}
                        </btn>
                    </td>
                    <!-- 
                        <td class="goods-page-ref-no">
                            javc2133
                        </td>
                    -->
                    <td class="goods-page-quantity">
                        <div class="product-quantity">
                            <boot-touchspin 
                                v-bind:value="parseInt(item.quantity)"
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
                    <tooltip :text="item.description" :enable="!isEmpty(item.description)">
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
    import {Tooltip, Modal, Btn} from 'uiv'
    import _ from 'lodash'
    export default {
        components: {
            BootTouchspin,
            Tooltip,
            Modal, 
            Btn
        },
        name: 'cart-page-component',
        props: ['initCart', 'baseUrl'],
        data: function () {
            return {
                cart: this.initCart,
                modal: {
                    show: false,
                    url: '',
                    title: '',
                    description: '',
                    conditions: []
                },
                debug: true
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
            modals: function () {
                return this.modalsArray
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
            },
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
                            _this.$forceUpdate()
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
                this.doAjax(
                    item, '/delfromcart', item.quantity, 
                    'delFromCart', false
                )
            },
            addToCart: function (item, quantity) {
                this.doAjax(
                    item, '/addtocart', quantity, 
                    'addToCart', false
                )
            },
            remFromCart: function (item, quantity) {
                this.doAjax(
                    item, '/remfromcart', quantity, 
                    'remFromCart', this.debug
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
            hasSymbol: function (val) {
                // var reg1 = /[&+-]{1,2}/
                var reg = /%/
                return reg.test(val) 
            },
            isEmpty: function (data) {
                return _.isEmpty(data)
            },
            toInteger: function (val) {
                return _.toInteger(val)
            },
            showModal: function (item) {
                this.modal.url = item.url
                this.modal.title = item.name
                this.modal.description = item.description
                this.modal.conditions = item.conditions
                this.modal.show = true
            }
        }
    }
</script>
