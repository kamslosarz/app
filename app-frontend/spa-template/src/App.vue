<template>
  <div class="container">
    <navigation items="navigationItems" />
    <div class="row">
      <div class="col-12 mt-4">
        <router-view />
      </div>
    </div>
  </div>
</template>

<script lang="ts">
    import {Component, Vue} from "vue-property-decorator";
    import Navigation from "@/components/Navigation.vue";

    @Component({
  components: {
    Navigation
  }
})
export default class App extends Vue {}
</script>

<style lang="scss">
@import "~bootstrap/dist/css/bootstrap.css";
</style>
