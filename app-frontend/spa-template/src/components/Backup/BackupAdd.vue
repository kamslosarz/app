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
  import {mapActions, mapState} from "vuex";

  @Component({
  components: {
    BackupForm
  },
  methods: {
    ...mapActions("backupItem", ["saveBackup"]),
    ...mapActions("toast", ["addToastMessage"])
  },
  computed: {
    ...mapState("backupItem", ["errors"])
  }
})
export default class BackupAdd extends Vue {
  entry!: BackupItem;
  saveBackup!: (backupItem: BackupItem) => Promise<BackupItemResponse>;
  addToastMessage!: (toastMessage: { title: string; body: string }) => {};

  save(event: MouseEvent) {
    event.stopPropagation();
    event.preventDefault();

    if (this.entry.date) {
      this.entry.date = this.getFormattedDate(new Date(this.entry.date));
    }

    this.saveBackup(this.entry).then((response: BackupItemResponse) => {
      this.addToastMessage({
        title: "Backup added",
        body: "Backup " + response.data.item.name + " was successfully added"
      });
    });
  }

  created() {
    this.entry = {
      date: this.getFormattedDate(new Date()),
      description: "",
      id: 0,
      name: ""
    };
  }

  getFormattedDate(date: Date): string {
    return date.toISOString().slice(0, 10);
  }
}
</script>
