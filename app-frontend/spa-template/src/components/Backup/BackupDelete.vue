<template>
  <div class="d-inline">
    <button
      class="btn btn-danger btn-sm delete-btn"
      v-on:click="confirmRemoveItem"
    >
      Delete
    </button>
    <transition name="fade">
      <div v-if="displayDeleteModal" class="d-inline delete-modal-container">
        <confirm-modal
          :class="{ active: displayDeleteModal }"
          @close="modalClosed"
          @confirm="modalConfirmed"
        />
      </div>
    </transition>
  </div>
</template>

<script lang="ts">
  import {Component, Prop, Vue} from "vue-property-decorator";
  import {BackupItem, BackupItemDeleteResponse} from "@/models/Backup";
  import {mapActions, mapMutations} from "vuex";

  @Component({
  methods: {
    ...mapMutations("backupList", ["itemDeleted"]),
    ...mapActions("backupItem", ["deleteBackup"]),
    ...mapActions("toast", ["addToastMessage"])
  }
})
export default class BackupDelete extends Vue {
  @Prop({
    required: true,
    default: null
  })
  item!: BackupItem;
  displayDeleteModal: boolean = false;
  deleteBackup!: (item: BackupItem) => BackupItemDeleteResponse;
  itemDeleted!: (item: BackupItem) => Promise<BackupItemDeleteResponse>;
  addToastMessage!: (toastMessage: { title: string; body: string }) => {};

  confirmRemoveItem(item: BackupItem) {
    this.displayDeleteModal = true;
  }

  modalConfirmed() {
    this.removeItem(this.item);
  }

  modalClosed() {
    this.displayDeleteModal = false;
  }

  async removeItem(item: BackupItem) {
    const response = await this.deleteBackup(item);
    if (response.success) {
      this.addToastMessage({
        title: "Backup removed",
        body: "Backup '" + item.name + "' was successfully removed"
      });
      this.itemDeleted(item);
      this.$emit("removed", item);
      this.modalClosed();
    }
  }
}
</script>