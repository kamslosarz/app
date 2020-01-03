<template>
  <div class="container">
    <loader v-if="!applicationReady" :is-loading="true" />
    <div class="col-12" v-else>
      <navigation items="navigationItems" />
      <div class="row">
        <div class="col-12 mt-4">
          <router-view />
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
  import {Component, Vue} from "vue-property-decorator";
  import Navigation from "@/components/Navigation.vue";
  import Loader from "@/components/Loader.vue";
  import {auth} from "@/services/services";
  import {AuthTokenResponse} from "@/models/Response";

  @Component({
  components: {
    Navigation,
    Loader
  }
})
export default class App extends Vue {
  applicationReady: boolean = false;
  created() {
    auth.generateAccessToken().then((response: AuthTokenResponse) => {
      this.applicationReady = auth.hasToken();
    });
  }
}
</script>

<style lang="scss">
@import "~bootstrap/dist/css/bootstrap.css";
</style>
