<template>
  <div class="itemsList">
    <loader :is-loading="loading" />
    <div class="row">
      <div class="col-12 mb-4">
        <div class="md-form mb-0">
          <input
            class="form-control"
            type="text"
            placeholder="Search"
            aria-label="Search"
            v-model="keyword"
          />
        </div>
      </div>
      <div class="col-4">
        <div class="list-group mb-4" id="list-tab" role="tablist">
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
        <Pagination
          v-if="showPagination"
          :pagination="pagination"
          @pageChanged="pageChanged"
        />
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
  import {BackupItem, BackupItemDeleteResponse, BackupListResponse} from "@/models/Backup";
  import {mapActions, mapState} from "vuex";
  import Loader from "@/components/Loader.vue";
  import BackupItemDetails from "@/components/BackupItemDetails.vue";
  import BackupItemEdit from "@/components/BackupItemEdit.vue";
  import Pagination from "@/components/Pagination.vue";
  import {Pagination as PaginationInterface} from "@/models/Response";
  import {AxiosPromise} from "axios";
  import {SearchPayload} from "@/models/Search";

  @Component({
  components: {
    Loader,
    BackupItemDetails,
    BackupItemEdit,
    Pagination
  },
  methods: {
    ...mapActions("backupList", ["getItems", "search"]),
    ...mapActions("backupItem", ["deleteItem"])
  },
  computed: {
    ...mapState("backupList", ["loading", "items", "pagination"])
  }
})
export default class BackupsListView extends Vue {
  items!: BackupItem[];
  activeItem: BackupItem | null = null;
  getItems!: (offset?: number) => AxiosPromise;
  deleteItem!: (item: BackupItem) => Promise<BackupItemDeleteResponse>;
  displayMode: string = "details";
  pagination!: PaginationInterface;
  keyword: string = "";
  search!: (searchPayload: SearchPayload) => Promise<BackupListResponse>;
  searchQueue: number = 0;

  @Watch("keyword")
  searchChanged(keyword: string) {
    this.searchQueue++;
    setTimeout(() => {
      this.searchQueue--;
      if (this.searchQueue === 0) {
        this.search({ keyword: keyword, offset: 0 });
      }
    }, 1000);
  }

  @Watch("items")
  itemsChanged(items: BackupItem[]) {
    this.activeItem = null;
    if (items.length) {
      this.activeItem = items[0];
    }
  }

  pageChanged(offset: number) {
    if (this.keyword) {
      let keyword = this.keyword;
      this.search({ keyword, offset });
    } else {
      this.getItems(offset);
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
    if (this.activeItem && this.activeItem.id === item.id) {
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

  get showPagination(): boolean {
    return this.pagination !== null;
  }
}
</script>
