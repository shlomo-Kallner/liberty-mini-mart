<template>
  <div>
    <div v-if="_.size(items) > 0" v-for="(slice, index) in itemSlices" :key="index" class="row product-list">
      <content-mini v-for="(item, idx) in slice" :key="idx" v-bind="item" ></content-mini>
    </div>
    <div v-else class="row">
      <div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">
        <div class="well">
          <h3>
            <i class="fa fa-exclamation-triangle"></i>
            <strong>We are Sorry!</strong>
            We Have no {{itemsType}} to Display!
          </h3>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

  import ContentMini from './contentItemMini.vue'
  import _ from 'lodash'
  import myUtils from '../../utils.js'

  export default {
    props: {
      items: Array,
      itemsType: {
        type: String,
        default: 'Items'
      },
      numPerRow: {
        type: Number,
        default: 3
      }
    },
    components: {
      ContentMini
    },
    data() {
      return {}
    },
    computed: {
      itemSlices: () => {
        return _.chunk(items, numPerRow)
      }
    },
    methods: {}
  }
</script>