<template>
  <div>
    <div class="input-group">
      <span class="input-group-btn">
        <btn type="link" size="sm" @click="addTick(1)" >
            <i :class="iconUp"></i>
        </btn>
      </span>
      <input type="text" class="form-control" placeholder="Search for...">
      <span class="input-group-btn">
        <btn type="link" size="sm" @click="subTick(1)" >
            <i :class="iconDown"></i>
        </btn>
      </span>
    </div><!-- /input-group -->
  </div>
</template>

<script>
import Btn from 'uiv'

  export default {
    components: {
      Btn
    },
    props: {
      value: {
        default: 1,
        type: Number
      },
      upIcon: {
        type: String,
        default: 'fa fa-angle-up'
      },
      downIcon: {
        type: String,
        default: 'fa fa-angle-down'
      }
    },
    data: function () {
      return {
        our_value: this.value,
        our_up_icon: this.upIcon,
        our_down_icon: this.downIcon
      }
    },
    computed: {
      iconUp: function () {
        return this.our_up_icon;
      },
      iconDown: function () {
        return this.our_down_icon
      }
    },
    methods: {
      addTick: function (val) {
        this.our_value += val;
      },
      subTick: function (val) {
        this.our_value -= val;
      }
    }
  }
</script>