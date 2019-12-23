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
          v-if="showDetails && activeItem"
          :item="activeItem"
          @remove="remove"
          @edit="edit"
        />
        <BackupItemEdit
          v-if="showEdit && activeItem"
          :item-id="activeItem.id"
          @itemUpdated="itemUpdated"
          @cancel="cancel"
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
  import BackupItemEdit from "@/components/BackupItemEdit.vue";

  @Component({
  components: {
    Loader,
    BackupItemDetails,
    BackupItemEdit
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
  displayMode: string = "details";

  @Watch("items")
  itemsChanged(items: BackupItem[]) {
    this.activeItem = null;
    if (items.length) {
      this.activeItem = items[0];
    }
  }
  select(item: BackupItem): void {
    this.activeItem = item;
    this.displayMode = "details";
  }
  edit(item: BackupItem): void {
    this.activeItem = item;
    this.displayMode = "edit";
  }
  remove(item: BackupItem): void {
    this.deleteItem(item).then((response: BackupItemDeleteResponse) => {
      this.$emit("itemDeleted", item);
    });
  }
  mounted() {
    this.$on("itemDeleted", (item: BackupItem) => {
      this.$store.commit(
        "backupList/setItems",
        this.items.filter((listItem: BackupItem) => item.id !== listItem.id)
      );
    });
  }
  itemUpdated(item: BackupItem) {
    if(this.activeItem && this.activeItem.id === item.id){
      this.activeItem = item;
    }
    let items: BackupItem[] = this.items;
    items[
      items.findIndex(listItem => {
        return listItem.id === item.id;
      })
    ] = item;
    this.$store.commit("backupList/setItems", items);
    this.$forceUpdate();
  }
  cancel() {
    this.displayMode = "details";
  }
  created(): void {
    this.getItems();
  }
  get showDetails(): boolean {
    return this.displayMode === "details";
  }
  get showEdit(): boolean {
    return this.displayMode === "edit";
  }
}
</script>
