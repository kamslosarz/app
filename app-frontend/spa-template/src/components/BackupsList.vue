<template>
  <div class="row">
    <loader :is-loading="loading" />
    <div class="col-12">
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
    <div class="col-4 mt-4">
      <div class="list-group mb-4" id="list-tab" role="tablist">
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
      <Pagination
        v-if="showPagination"
        :pagination="pagination"
        @pageChanged="pageChanged"
      />
    </div>
    <div class="col-8 mt-4">
      <slot></slot>
    </div>
  </div>
</template>

<script lang="ts">
  import {Component, Vue, Watch} from "vue-property-decorator";
  import {mapActions, mapState} from "vuex";
  import {Pagination as PaginationInterface} from "@/models/Response";
  import {BackupItem, BackupListResponse} from "@/models/Backup";
  import {SearchPayload} from "@/models/Search";
  import Pagination from "@/components/Pagination.vue";
  import Loader from "@/components/Loader.vue";

  @Component({
  components: {
    Pagination,
    Loader
  },
  methods: {
    ...mapActions("backupList", ["getItems", "search"])
  },
  computed: {
    ...mapState("backupList", ["loading", "items", "pagination"])
  }
})
export default class BackupsList extends Vue {
  pagination!: PaginationInterface;
  keyword: string = "";
  getItems!: (offset?: number) => Promise<BackupListResponse>;
  search!: (searchPayload: SearchPayload) => Promise<BackupListResponse>;
  searchQueue: number = 0;
  items!: BackupItem[];
  activeItemId: number | null = null;

  @Watch("keyword")
  keywordChanged(keyword: string) {
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
    this.activeItemId = null;
    if (items.length) {
      this.select(items[0]);
    }
  }

  created(): void {
    this.getItems();
    if (this.items.length && !this.activeItemId) {
      this.select(this.items[0]);
    }
  }

  select(item: BackupItem): void {
    this.activeItemId = item.id;
    this.$emit("selected", item);
  }

  pageChanged(offset: number) {
    if (this.keyword) {
      let keyword = this.keyword;
      this.search({ keyword, offset });
    } else {
      this.getItems(offset);
    }
  }

  get showPagination(): boolean {
    return this.pagination !== null;
  }
}
</script>
