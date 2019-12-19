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
            :class="{ active: activeItem && activeItem.id === item.id }"
            href="#"
            @click="select(item)"
          >
            {{ item.name }}
          </a>
        </div>
      </div>
      <div class="col-8">
        <BackupItemDetails
          v-if="activeItem"
          :item="activeItem"
          @remove="remove"
        />
      </div>
    </div>
  </div>
</template>

<script lang="ts">
  import {Component, Vue, Watch} from "vue-property-decorator";
  import {BackupItem, BackupItemDeleteResponse} from "@/models/Backup";
  import {mapActions, mapState} from "vuex";
  import Loader from "@/components/Loader.vue";
  import BackupItemDetails from "@/components/BackupItemDetails.vue";

  @Component({
  components: {
    Loader,
    BackupItemDetails
  },
  methods: {
    ...mapActions("backupList", ["getItems"]),
    ...mapActions("backupItem", ["deleteItem"])
  },
  computed: {
    ...mapState("backupList", ["loading", "items"])
  }
})
export default class BackupsListView extends Vue {
  items!: BackupItem[];
  activeItem: BackupItem | null = null;
  getItems!: () => BackupItem[];
  deleteItem!: (item: BackupItem) => Promise<BackupItemDeleteResponse>;

  @Watch("items")
  itemsChanged(items: BackupItem[]) {
    this.activeItem = null;
    if (items.length) {
      this.activeItem = items[0];
    }
  }
  select(item: BackupItem): void {
    this.activeItem = item;
  }
  edit(item: BackupItem): void {
    this.$router.push("/edit/" + item.id);
  }
  itemDeleted(item: BackupItem) {
    this.$store.commit('backupList/setItems', this.items.filter((listItem)=>item.id !== listItem.id));
  }
  remove(item: BackupItem): void {
    this.deleteItem(item).then(response => {
      // @ts-ignore
      if (response.data && response.data[0] == "ok") {
        this.itemDeleted(item);
      }
    });
  }

  created(): void {
    this.getItems();
  }
}
</script>
