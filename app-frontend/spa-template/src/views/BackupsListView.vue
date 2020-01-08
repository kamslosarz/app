<template>
  <div class="itemsList">
    <BackupsList @selected="itemSelected" @search="searched">
      <div class="col-8">
        <BackupItemDetails
          v-if="showDetails && activeItem"
          :item="activeItem"
          @edit="itemEdit"
          @removed="itemRemoved"
        />
        <BackupItemEdit
          v-if="showEdit && activeItem"
          :item-id="activeItem.id"
          @updated="itemUpdated"
          @canceled="editCanceled"
        />
      </div>
    </BackupsList>
  </div>
</template>

<script lang="ts">
  import {Component, Vue} from "vue-property-decorator";
  import {BackupItem} from "@/models/Backup";
  import Loader from "@/components/Loader.vue";
  import BackupItemDetails from "@/components/BackupItemDetails.vue";
  import BackupItemEdit from "@/components/BackupItemEdit.vue";
  import BackupsList from "@/components/BackupsList.vue";

  @Component({
  components: {
    Loader,
    BackupItemDetails,
    BackupItemEdit,
    BackupsList
  }
})
export default class BackupsListView extends Vue {
  displayMode: string = "details";
  activeItem: BackupItem | null = null;

  itemSelected(item: BackupItem): void {
    this.displayMode = "details";
    this.activeItem = item;
  }

  searched(keyword: string) {
    // if (this.$store.state.backupList.items.length) {
    //   this.itemSelected(this.$store.state.backupList.items[0]);
    // }
  }

  itemEdit(item: BackupItem): void {
    this.activeItem = item;
    this.displayMode = "edit";
  }

  itemRemoved(item: BackupItem): void {
    this.$forceUpdate();
  }

  itemUpdated(item: BackupItem): void {
    if (this.activeItem && this.activeItem.id === item.id) {
      this.activeItem = item;
    }
    this.$forceUpdate();
  }

  editCanceled() {
    this.displayMode = "details";
  }

  get showDetails(): boolean {
    return this.displayMode === "details";
  }

  get showEdit(): boolean {
    return this.displayMode === "edit";
  }
}
</script>
