<template>
    <tr>
        <td class="goods-page-image">
            <a :href="item.img" class="btn btn-default fancybox.image fancybox-button">
                <img :src="item.img" :alt="item.name" class="img-responsive">
            </a>
        </td>
        <td class="goods-page-description">
            <btn type="link" @click="showModal(item)">
                {{item.name}}
            </btn>
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
                <i :class="'fa' + currency"></i>
                {{ item.price }}
            </strong>
        </td>
        <td class="goods-page-price">
            <strong>
                <i :class="'fa' + currency"></i>
                {{ item.priceCalc }}
            </strong>
        </td>
        <td class="goods-page-total">
            <strong>
                <i :class="'fa' + currency"></i>
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
          item: Object,
          baseUrl: String,
          currency: String
      }
  }
</script>

<style scoped>

</style>

