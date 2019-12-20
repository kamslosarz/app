<template>
  <nav class="navbar navbar-expand-sm bg-light">
    <ul class="navbar-nav" v-if="items && items.length">
      <li class="nav-item" v-for="item in items" :key="item.id">
        <router-link class="nav-link" :to="item.href">
          {{ item.title }}
        </router-link>
      </li>
    </ul>
  </nav>
</template>

<script lang="ts">
  import {NavigationItem} from "@/models/Navigation";
  import {Component, Vue} from "vue-property-decorator";
  import {mapActions, mapState} from "vuex";

  @Component({
  methods: mapActions("navigationList", ["getItems"]),
  computed: {
    ...mapState("navigationList", ["loading", "items"])
  }
})
export default class Navigation extends Vue {
  getItems!: () => NavigationItem[];
  items!: NavigationItem[];

  created() {
    this.getItems();
  }
}
</script>

<style lang="scss">
.router-link-active {
  color: #007bff78;
}
</style>
