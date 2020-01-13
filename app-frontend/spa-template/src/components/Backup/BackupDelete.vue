<template>
  <div class="d-inline">
    <button class="btn btn-danger btn-sm" v-on:click="confirmRemoveItem">
      Delete
    </button>
    <transition name="fade">
      <confirm-modal
        v-if="showModal"
        @close="modalClosed"
        @confirm="modalConfirmed"
      />
    </transition>
  </div>
</template>

<script lang="ts">
  import {Component, Prop, Vue} from "vue-property-decorator";
  import {BackupItem, BackupItemDeleteResponse} from "@/models/Backup";
  import {mapActions} from "vuex";

  @Component({
  methods: {
    ...mapActions("backupList", ["itemDeleted"]),
    ...mapActions("backupItem", ["deleteBackup"])
  }
})
export default class BackupDelete extends Vue {
  @Prop({
    required: true,
    default: null
  })
  item!: BackupItem;
  showModal: boolean = false;
  deleteBackup!: (item: BackupItem) => Promise<BackupItemDeleteResponse>;
  itemDeleted!: (item: BackupItem) => Promise<BackupItemDeleteResponse>;

  confirmRemoveItem(item: BackupItem) {
    this.showModal = true;
  }

  modalConfirmed() {
    this.removeItem(this.item);
  }

  modalClosed() {
    this.showModal = false;
  }
  removeItem(item: BackupItem) {
    this.deleteBackup(item).then((response: BackupItemDeleteResponse) => {
      this.$emit("itemDeleted", item);
      this.itemDeleted(item);
    });
  }
}
</script>