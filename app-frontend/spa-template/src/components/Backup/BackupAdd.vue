<template>
  <div class="col-md-12">
    <backup-form :entry="entry" :errors="errors">
      <template v-slot:form-bottom>
        <button class="btn btn-sm btn-primary" v-on:click="save">
          Save
        </button>
      </template>
    </backup-form>
  </div>
</template>

<script lang="ts">
  import {Component, Vue} from "vue-property-decorator";
  import BackupForm from "@/components/Backup/BackupForm.vue";
  import {BackupItem, BackupItemResponse} from "@/models/Backup";
  import {mapActions, mapMutations, mapState} from "vuex";

  @Component({
  components: {
    BackupForm
  },
  methods: {
    ...mapActions("backupItem", ["saveBackup"]),
    ...mapActions("toast", ["addToastMessage"]),
    ...mapMutations("backupItem", ["setErrors"])
  },
  computed: {
    ...mapState("backupItem", ["errors"])
  }
})
export default class BackupAdd extends Vue {
  entry!: BackupItem;
  saveBackup!: (backupItem: BackupItem) => BackupItemResponse;
  addToastMessage!: (toastMessage: { title: string; body: string }) => {};
  setErrors!: (error: []) => {};

  async save() {
    const response: BackupItemResponse = await this.saveBackup(this.entry);
    this.showSuccessToast(response.data.item);
    this.resetEntry();
  }

  created() {
    this.entry = this.getEntry();
    this.setErrors([]);
  }

  showSuccessToast(item: BackupItem) {
    this.addToastMessage({
      title: "Backup added",
      body: "Backup " + item.name + " was successfully added"
    });
  }

  resetEntry() {
    this.entry = this.getEntry();
    this.$forceUpdate();
  }

  getEntry(): BackupItem {
    return {
      date: new Date(Date.now()).toISOString(),
      description: "",
      id: 0,
      name: ""
    };
  }
}
</script>
