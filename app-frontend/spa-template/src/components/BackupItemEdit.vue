<template>
  <div>
    <loader :is-loading="loading" />
    <backup-form
      v-if="item"
      :errors="responseErrors"
      :cancelAble="true"
      :item="item"
      @save="save"
      @cancel="$emit('canceled')"
    >
      <template v-slot:form-message>
        <div class="alert alert-success" role="alert" v-if="success">
          {{ successMessage }}
        </div>
      </template>
      <template v-slot:form-buttons>
        <input
          type="button"
          class="btn btn-danger btn-sm"
          v-on:click="remove(item)"
          value="Delete"
        />
      </template>
    </backup-form>
  </div>
</template>
<script lang="ts">
  import {Component, Prop, Vue} from "vue-property-decorator";
  import {mapActions, mapState} from "vuex";
  import {BackupItem, BackupItemDeleteResponse, BackupItemResponse} from "@/models/Backup";
  import Loader from "@/components/Loader.vue";
  import BackupForm from "@/components/BackupForm.vue";

  @Component({
  components: {
    Loader,
    BackupForm
  },
  methods: {
    ...mapActions("backupItem", ["updateItem", "getItem", "deleteItem"])
  },
  computed: {
    ...mapState("backupItem", ["loading", "responseErrors", "item"]),
    ...mapState("backupList", ["items"])
  }
})
export default class BackupItemEdit extends Vue {
  @Prop({ required: true })
  itemId!: number;
  loading!: boolean;
  success: boolean = false;
  items!: BackupItem[];
  getItem!: (itemId: number) => Promise<BackupItemResponse>;
  updateItem!: (item: BackupItem) => Promise<BackupItemResponse>;
  deleteItem!: (item: BackupItem) => Promise<BackupItemDeleteResponse>;

  remove(deleteItem: BackupItem): void {
    if (confirm("Are you sure?")) {
      this.deleteItem(deleteItem).then((response: BackupItemDeleteResponse) => {
        this.$store.dispatch("backupList/getItems");
        this.$emit("removed", deleteItem);
      });
    }
  }

  save(item: BackupItem): void {
    this.success = false;
    this.updateItem(item).then((response: BackupItemResponse) => {
      this.success = true;
      this.$store.dispatch("backupList/updateItem", item);
      this.$emit("updated", item);
    });
  }

  created() {
    this.getItem(this.itemId);
  }

  get successMessage(): string {
    return "Updated successfully";
  }
}
</script>
