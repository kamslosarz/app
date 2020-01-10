<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <loader :is-loading="loading" />
    <ul v-if="items && items.length" class="navbar-nav">
      <li
        v-for="item in items"
        :key="item.id"
        class="nav-item"
        :class="{ active: isActive(item) }"
      >
        <router-link class="nav-link" :to="item.href">
          {{ item.title }}
        </router-link>
      </li>
    </ul>
  </nav>
</template>

<script lang="ts">
  import {Component, Vue} from "vue-property-decorator";
  import {mapActions, mapState} from "vuex";
  import {NavigationItem, NavigationResponse} from "@/models/NavigationItem";

  @Component({
  methods: {
    ...mapActions("navigation", ["getNavigation"])
  },
  computed: {
    ...mapState("navigation", ["items", "loading"])
  }
})
export default class Navigation extends Vue {
  getNavigation!: () => Promise<NavigationResponse>;
  items!: NavigationItem[];

  created() {
    this.getNavigation();
  }

  isActive(item: NavigationItem): boolean {
    return this.$route.path === item.href;
  }
}
</script>
