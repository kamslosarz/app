<template>
  <div class="col-md-12">
    <search @searched="searched" />
    <table class="table mt-3">
      <thead>
        <tr>
          <th>Name</th>
          <th>Description</th>
          <th>Create Date</th>
          <th>&nbsp;</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="item in items" v-bind:key="item.id">
          <td class="w-25">#{{ item.id }} {{ item.name }}</td>
          <td class="w-35">{{ item.description }}</td>
          <td class="w-25">{{ item.date }}</td>
          <td class="w-15">
            <div class="btn-group-sm float-right">
              <backup-edit :item="item" @updated="itemUpdated(item)" />
              <span class="m-1" />
              <backup-delete :item="item" @removed="itemRemoved(item)" />
            </div>
          </td>
        </tr>
      </tbody>
    </table>
    <pagination
      :pagination="pagination"
      :page="page"
      @pageSelected="pageSelected"
    />
  </div>
</template>

<script lang="ts">
  import {Component, Vue} from "vue-property-decorator";
  import {mapActions, mapMutations, mapState} from "vuex";
  import {BackupItem, BackupListResponse} from "@/models/Backup";
  import BackupDelete from "@/components/Backup/BackupDelete.vue";
  import BackupEdit from "@/components/Backup/BackupEdit.vue";
  import Pagination from "@/components/Pagination/Pagination.vue";
  import {Page, PaginationInterface} from "@/models/PaginationModel";
  import Search from "@/components/Search/Search.vue";

  @Component({
  components: {
    Pagination,
    BackupEdit,
    BackupDelete,
    Search
  },
  methods: {
    ...mapActions("backupList", ["getBackupList", "search"]),
    ...mapMutations("backupList", ["updateKeyword", "updatePage"])
  },
  computed: {
    ...mapState("backupList", ["items", "pagination", "page", "keyword"])
  }
})
export default class BackupList extends Vue {
  getBackupList!: (offset?: number) => Promise<BackupListResponse>;
  search!: (offset?: number) => Promise<BackupListResponse>;
  updatePage!: (page: number) => {};
  pagination!: PaginationInterface;
  updateKeyword!: (keyword: string) => {};
  keyword!: string;

  itemUpdated(item: BackupItem) {
    this.$forceUpdate();
  }

  itemRemoved(item: BackupItem) {
    this.reloadList().then((response: BackupListResponse) => {
      if (this.currentPage > this.lastPage) {
        this.loadPage(this.lastPage);
      }
    });
  }

  searched(keyword: string) {
    this.updatePage(0);
    this.updateKeyword(keyword);
    this.reloadList();
  }

  pageSelected(page: Page) {
    this.loadPage(page.page);
  }

  loadPage(page: number) {
    this.updatePage(page);
    this.reloadList();
  }

  reloadList(): Promise<BackupListResponse> {
    if (this.keyword.length) {
      return this.search(this.offset);
    } else {
      return this.getBackupList(this.offset);
    }
  }

  created() {
    this.reloadList();
  }

  get lastPage(): number {
    if (this.pagination) {
      const total = this.pagination.total;
      const perPage = this.pagination.perPage;
      const lastPage = Math.ceil(total / perPage);
      return lastPage > 0 ? lastPage - 1 : 0;
    }

    return 0;
  }

  get currentPage(): number {
    return this.pagination.page;
  }

  get offset() {
    if (this.pagination) {
      return this.pagination.page * this.pagination.perPage;
    }
    return 0;
  }
}
</script>
<style lang="scss">
.w-35 {
  width: 35%;
}
.w-15 {
  width: 15%;
}
</style>
