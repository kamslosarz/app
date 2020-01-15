<template>
  <transition name="fade">
    <div v-if="isLoading" class="loader overlay">
      <div class="spinner-border"></div>
    </div>
  </transition>
</template>

<script lang="ts">
  import {Component, Prop, Vue} from "vue-property-decorator";

  @Component
export default class Loader extends Vue {
  @Prop({ required: true, default: false })
  isLoading!: boolean;
}
</script>

<style lang="scss">
.loader {
  z-index: 20;
}
.overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: #0000002e;
}
.overlay .spinner-border {
  margin: auto;
  left: 50%;
  top: 50%;
  position: absolute;
  z-index: 20;
}
</style>
