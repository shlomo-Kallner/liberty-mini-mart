<template>
  <div v-if="isLoading">
    <uiv-progress-bar v-model="ourProgress" type="info" label label-text="Loading......Please wait." striped active></uiv-progress-bar>
  </div>
</template>

<script>
  import { ProgressBar as UivProgressBar } from 'uiv';
  import Velocity from 'velocity-animate'
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
        ourProgress: 1,
        tween: new Tween.Tween(this)
          .to({ourProgress: 100}, 10000)
          .repeat(Infinity)
          .start()
      }
    },
    computed: {
      isLoading: () => this.loading,
      ourProgress: () => {
        return 
      }
    },
    methods: {
      
    },
  }
</script>