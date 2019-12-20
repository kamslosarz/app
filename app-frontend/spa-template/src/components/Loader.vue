<template>
  <div class="global-loader">
    <div v-if="isLoading" class="spinner-grow" role="status">
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
