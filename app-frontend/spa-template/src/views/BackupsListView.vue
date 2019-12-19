<template>
  <div class="itemsList">
    <loader :is-loading="loading" />
    <div class="row">
      <div class="col-4">
        <div class="list-group" id="list-tab" role="tablist">
          <a
            v-for="item in items"
            :key="item.id"
            class="list-group-item list-group-item-action"
            :class="{ active: activeItemId === item.id }"
            href="#"
            @click="select(item)"
          >
            {{ item.name }}
          </a>
        </div>
      </div>
      <div class="col-8">
        <div class="tab-content" id="nav-tabContent">
          <div
            class="tab-pane"
            :class="{ active: activeItemId === item.id }"
            v-for="item in items"
            :key="item.id"
          >
            {{ item.date }}, {{ item.description }}
            <br />
            <input type="button" value="Edit" @click="edit(item)" />
            <input type="button" value="Delete" @click="remove(item)" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
  import {Component, Vue, Watch} from "vue-property-decorator";
  import {BackupItem} from "@/models/Backup";
  import {mapActions, mapState} from "vuex";
  import Loader from "@/components/Loader.vue";

  @Component({
  components: {
    Loader
  },
  methods: mapActions("backupList", ["getItems"]),
  computed: {
    ...mapState("backupList", ["loading", "items"])
  }
})
export default class BackupsListView extends Vue {
  items!: BackupItem[];
  activeItemId: number | null = null;
  getItems!: () => BackupItem[];

  @Watch("items")
  itemsChanged(items: BackupItem[]) {
    if (items.length) {
      this.activeItemId = items[0].id;
    }
  }

  select(item: BackupItem): void {
    this.activeItemId = item.id;
  }

  edit(item: BackupItem): void {
    this.$router.push("/edit/" + item.id);
  }

  remove(item: BackupItem): void {}

  created(): void {
    this.getItems();
  }
}
</script>
