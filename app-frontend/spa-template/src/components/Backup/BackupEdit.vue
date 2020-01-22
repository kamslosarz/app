<template>
  <div class="d-inline">
    <transition name="fade">
      <button class="btn btn-primary btn-sm edit-btn" v-on:click="editItem">
        Edit
      </button>
    </transition>
    <transition name="fade">
      <div v-if="displayEditModal" class="d-inline">
        <modal @close="closeModal">
          <template v-slot:title> Edit item {{ item.name }} </template>
          <template v-slot:body>
            <backup-form :entry="entry" :errors="errors" />
          </template>
          <template v-slot:buttons>
            <button class="btn btn-sm btn-primary save-btn" v-on:click="save">
              Save
            </button>
            <button class="btn btn-sm" v-on:click="closeModal">Close</button>
          </template>
        </modal>
      </div>
    </transition>
  </div>
</template>

<script lang="ts">
  import {Component, Prop, Vue} from "vue-property-decorator";
  import {BackupItem, BackupItemResponse} from "@/models/Backup";
  import Modal from "@/components/Modal/Modal.vue";
  import BackupForm from "@/components/Backup/BackupForm.vue";
  import {mapActions, mapMutations, mapState} from "vuex";

  @Component({
  components: {
    Modal,
    BackupForm
  },
  methods: {
    ...mapActions("backupItem", ["updateBackup"]),
    ...mapMutations("backupList", ["updateItem"]),
    ...mapMutations("backupItem", ["setErrors"]),
    ...mapActions("toast", ["addToastMessage"])
  },
  computed: {
    ...mapState("backupItem", ["errors"])
  }
})
export default class BackupEdit extends Vue {
  @Prop({
    required: true
  })
  item!: BackupItem;
  entry!: BackupItem;
  displayEditModal: boolean = false;
  updateBackup!: (item: BackupItem) => Promise<BackupItemResponse>;
  updateItem!: (item: BackupItem) => void;
  addToastMessage!: (toastMessage: { title: string; body: string }) => {};
  setErrors!: (error: []) => {};

  async save() {
    const response: BackupItemResponse = await this.updateBackup(
      this.preparedEntry
    );
    if (response.success) {
      let item: BackupItem = response.data.item;
      this.updateItem(item);
      this.addToastMessage({
        title: "Backup Updated",
        body: "Backup '" + item.name + "' was successfully updated"
      });
      this.$emit("updated", item);
      this.closeModal();
    }
  }

  closeModal() {
    this.displayEditModal = false;
  }

  editItem(item: BackupItem) {
    this.displayEditModal = true;
  }

  created() {
    this.entry = JSON.parse(JSON.stringify(this.item));
    this.setErrors([]);
  }

  get preparedEntry(): BackupItem {
    let entry = this.entry;
    const date = new Date(entry.date);
    entry.date = date.toISOString().slice(0, 10);

    return entry;
  }
}
</script>
