<template>
  <div>
    <loader :is-loading="loading" />
    <backup-form @save="save" :errors="responseErrors" :item="itemEntry">
      <template v-slot:form-message>
        <div class="alert alert-success" role="alert" v-if="success">
          {{ successMessage }}
        </div>
      </template>
    </backup-form>
  </div>
</template>
<script lang="ts">
  import {Component, Vue} from "vue-property-decorator";
  import BackupForm from "@/components/BackupForm.vue";
  import {BackupItem, BackupItemResponse} from "@/models/Backup";
  import {mapActions, mapState} from "vuex";
  import Loader from "@/components/Loader.vue";

  @Component({
  components: {
    Loader,
    BackupForm
  },
  methods: {
    ...mapActions("backupItem", ["saveItem", "getItem"])
  },
  computed: {
    ...mapState("backupItem", ["loading", "responseErrors"])
  }
})
export default class AddBackupView extends Vue {
  loading!: boolean;
  saveItem!: (item: Object) => Promise<BackupItemResponse>;
  success: boolean = false;
  itemEntry: BackupItem = this.getNewItemEntry();

  savedSuccessFully() {
    this.success = true;
    this.itemEntry = this.getNewItemEntry();
  }

  getNewItemEntry(): BackupItem {
    return {
      id: null,
      name: "",
      description: "",
      date: new Date().toISOString().slice(0, 10)
    };
  }

  save(item: Object) {
    this.success = false;
    this.saveItem(item).then((response: BackupItemResponse) => {
      this.savedSuccessFully();
    });
  }

  get successMessage(): string {
    return "Added successfully";
  }
}
</script>
