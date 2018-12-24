<template>
  <div v-if="isLoading">
    <uiv-progress-bar v-model="progress" type="info" label label-text="Loading......Please wait." striped active></uiv-progress-bar>
  </div>
</template>

<script>
  import { ProgressBar as UivProgressBar } from 'uiv';
  // import Velocity from 'velocity-animate'
  import Tween from '@tweenjs/tween.js'

  export default {
    props: {
      loading: Boolean
    },
    components: {
      UivProgressBar
    },
    data () {
      return {
        progress: 1,
        tween: null
      }
    },
    created: function () {
      this.tween = new Tween.Tween(this.$data)
          .to({progress: 100}, 10000)
          .repeat(Infinity)
      if (this.loading) {
        this.tween.start()
      }
    },
    watch: {
      progress: function () {
        function animate() {
          if (Tween.update()) {
            requestAnimationFrame(animate)
          }
        }
        animate()
      },
      loading: function (newLoad, oldLoad) {
        if (newLoad) {
          this.tween.start()
        } else if (!newLoad) {
          this.tween.stop()
        }
      }
    }
  }
</script>