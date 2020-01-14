<template>
  <div class="col-md-12">
    <toast />
    <loader :is-loading="isLoading" />
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
          <td>#{{ item.id }} {{ item.name }}</td>
          <td>{{ item.description }}</td>
          <td>{{ item.date }}</td>
          <td>
            <div class="btn-group-sm">
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
  import {mapActions, mapGetters, mapState} from "vuex";
  import {BackupItem, BackupListResponse} from "@/models/Backup";
  import BackupDelete from "@/components/Backup/BackupDelete.vue";
  import BackupEdit from "@/components/Backup/BackupEdit.vue";
  import Toast from "@/components/Toast/Toast.vue";
  import Pagination from "@/components/Pagination/Pagination.vue";
  import {Page, PaginationInterface} from "@/models/PaginationModel";

  @Component({
  components: {
    Pagination,
    BackupEdit,
    BackupDelete,
    Toast
  },
  methods: {
    ...mapActions("backupList", ["getBackupList", "updatePage"]),
    ...mapActions("toast", ["addToastMessage"])
  },
  computed: {
    ...mapState("backupItem", ["item"]),
    ...mapState("backupList", ["items", "pagination", "page"]),
    ...mapGetters(["isLoading"])
  }
})
export default class BackupList extends Vue {
  getBackupList!: (offset?: number) => Promise<BackupListResponse>;
  addToastMessage!: (toastMessage: {
    title: string;
    body: string;
    date?: Date;
    duration?: number;
    type?: string;
  }) => {};
  updatePage!: (page: number) => {};
  pagination!: PaginationInterface;

  itemUpdated(item: BackupItem) {
    this.addToastMessage({
      title: "Backup Updated",
      body: "Backup '" + item.name + "' was successfully updated"
    });
    this.$forceUpdate();
  }

  itemRemoved(item: BackupItem) {
    this.addToastMessage({
      title: "Backup removed",
      body: "Backup '" + item.name + "' was successfully removed"
    });
    this.reloadList().then((response: BackupListResponse) => {
      if (this.currentPage > this.lastPage) {
        this.loadPage(this.lastPage);
      }
    });
  }

  pageSelected(page: Page) {
    this.loadPage(page.page);
  }

  loadPage(page: number) {
    this.updatePage(page);
    this.reloadList();
  }

  reloadList(): Promise<BackupListResponse> {
    return this.getBackupList(this.offset);
  }

  created() {
    this.getBackupList();
  }

  get lastPage(): number {
    if (this.pagination) {
      return Math.ceil(this.pagination.total / this.pagination.perPage) - 1;
    }

    return 0;
  }

  get currentPage(): number {
    return this.pagination.page;
  }

  get offset() {
    return this.pagination.page * this.pagination.perPage;
  }
}
</script>
