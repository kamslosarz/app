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
          <td>{{ item.name }}</td>
          <td>{{ item.description }}</td>
          <td>{{ item.date }}</td>
          <td>
            <div class="btn-group-sm">
              <backup-edit :item="item" @updated="itemUpdated" />
              <span class="m-1" />
              <backup-delete :item="item" />
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script lang="ts">
  import {Component, Vue} from "vue-property-decorator";
  import {mapActions, mapGetters, mapState} from "vuex";
  import {BackupItem, BackupListResponse} from "@/models/Backup";
  import BackupDelete from "@/components/Backup/BackupDelete.vue";
  import BackupEdit from "@/components/Backup/BackupEdit.vue";
  import {ToastMessage} from "@/models/ToastMessage";
  import Toast from "@/components/Toast/Toast.vue";

  @Component({
  components: {
    BackupEdit,
    BackupDelete,
    Toast
  },
  methods: {
    ...mapActions("backupList", ["getBackupList"]),
    ...mapActions("toast", ["addToastMessage"])
  },
  computed: {
    ...mapState("backupItem", ["item"]),
    ...mapState("backupList", ["items"]),
    ...mapGetters(["isLoading"])
  }
})
export default class BackupList extends Vue {
  getBackupList!: (offset?: number) => Promise<BackupListResponse>;
  addToastMessage!: (toastMessage: ToastMessage) => {};

  itemUpdated(item: BackupItem) {
    this.addToastMessage({
      title: "Backup Update",
      body: "Backup '" + item.name + "' was successfully updated",
      date: new Date(),
      duration: 10
    });
    this.$forceUpdate();
  }

  created() {
    this.getBackupList();
  }
}
</script>
