<template>
  <div>
    <div class="input-group">
      <span class="input-group-btn">
        <btn type="link" size="sm" @click="addTick(1, true)" >
            <i :class="iconUp"></i>
        </btn>
      </span>
      <input 
        type="text" class="form-control text-center"
        @wheel="onWheel"
        pattern="\d*"
        @mouseup="selectInputValue"
        @keydown.prevent.up="addTick(1, false)"
        @keydown.prevent.down="subTick(1, false)"
        :readonly="isReadOnly"
        v-model="our_value"
        >
      <span class="input-group-btn">
        <btn type="link" size="sm" @click="subTick(1, true)" >
            <i :class="iconDown"></i>
        </btn>
      </span>
    </div><!-- /input-group -->
  </div>
</template>

<script>
  import {Btn} from 'uiv'

  export default {
    components: {
      Btn
    },
    props: {
      value: {
        type: Number,
        required: true
      },
      upIcon: {
        type: String,
        default: 'fa fa-angle-up'
      },
      downIcon: {
        type: String,
        default: 'fa fa-angle-down'
      },
      readonly: {
        type: Boolean,
        default: false
      },
      extraControls: {
        type: Boolean,
        default: false
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
      },
      isReadOnly: function () {
        return !this.extraControls || this.readonly
      }
    },
    methods: {
      addTick: function (val, isButton) {
        if (!this.readonly) {
          if (isButton || this.extraControls) {
            this.our_value += val;
            this.changeVal(this.our_value)
          }
        }
      },
      subTick: function (val, isButton) {
        if (!this.readonly) {
          if (isButton || this.extraControls) {
            this.our_value -= val;
            this.changeVal(this.our_value)
          }
        }
      },
      onWheel: function (e) {
        if (!this.isReadOnly) {
          e.preventDefault()
          var val = Math.floor(e.deltaY)
          this.our_value += val
          this.changeVal(this.our_value)
        }
      },
      changeVal: function (val) {
        if (!this.readonly) {
          this.$emit('update:value', val)
        }
      },
      selectInputValue: function (e) {
        // mouseup should be prevented!
        // See various comments in https://stackoverflow.com/questions/3272089/programmatically-selecting-text-in-an-input-field-on-ios-devices-mobile-safari
        e.target.select();
      }
    }
  }
</script>

<style scoped> 
  
  .input-group {
    width: 70px !important;
  }
  .input-group .input-group-btn:first-child button {
    top: 0 !important;
  }
  .input-group .input-group-btn:last-child button {
    bottom: 0 !important;
  }
  
</style>

