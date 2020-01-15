<template>
  <div class="container">
    <navigation />
    <div class="panel panel-default">
      <div class="panel-body">
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
