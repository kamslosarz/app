<template>
  <div v-if="isLoading" class="global-loader">
    <div class="spinner-grow" role="status">
      <span class="sr-only">Loading...</span>
    </div>
  </div>
</template>

<script lang="ts">
  import {Component, Prop, Vue} from "vue-property-decorator";

  @Component({})
export default class Loader extends Vue {
  @Prop({ required: true, default: false })
  isLoading!: boolean;
}
</script>
<style lang="scss">
.global-loader {
  position: fixed;
  left: 0;
  top: 0;
  height: 100%;
  width: 100%;
  background: #00000030;
  z-index: 99999;
}
.global-loader .spinner-grow {
  position: absolute;
  top: 50%;
  left: 50%;
  margin: auto;
  z-index: 99999;
}
</style>
