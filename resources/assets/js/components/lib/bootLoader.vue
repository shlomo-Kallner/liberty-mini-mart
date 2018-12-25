<template>
  <div v-if="loading">
    <uiv-progress-bar v-model="progress" type="info" label label-text="Loading......Please wait." striped active></uiv-progress-bar>
  </div>
</template>

<script>
  import { ProgressBar as UivProgressBar } from 'uiv';
  // import Velocity from 'velocity-animate'
  import Tween from '@tweenjs/tween.js'

  export default {
    props: {
      loading: {
        type: Boolean,
        default: true
      },
      duration: {
        type: Number,
        default: 10000
      },
      val: {
        type: Number,
        default: 1,
        validator: function (value) {
          return value >= 1 && value <= 100
        }
      }
    },
    components: {
      UivProgressBar
    },
    data () {
      return {
        progress: this.val,
        tween: null
      }
    },
    created: function () {
      this.tween = new Tween.Tween(this.$data)
          .to({progress: 100}, this.duration)
          .repeat(Infinity)
      if (this.loading) {
        this.tween.start()
        this.animate()
      }
    },
    watch: {
      progress: function (val) {
        this.animate()
      },
      loading: function (newLoad, oldLoad) {
        if (newLoad) {
          this.tween.start()
        } else if (!newLoad) {
          this.tween.stop()
        }
      },
      val: function (value) {
        this.progress = value
      }
    },
    methods: {
      animate: function () {
        if (Tween.update()) {
          requestAnimationFrame(this.animate)
        }
      }
    }
  }
</script>