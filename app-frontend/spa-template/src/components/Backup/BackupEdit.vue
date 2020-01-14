<template>
  <div class="d-inline">
    <button class="btn btn-primary btn-sm" v-on:click="editItem">
      Edit
    </button>
    <modal v-if="displayEditModal" @close="close">
      <template v-slot:title> Edit item {{ item.name }} </template>
      <template v-slot:body>
        <backup-form :entry="itemEntry" :errors="errors" />
      </template>
      <template v-slot:buttons>
        <button class="btn btn-sm btn-primary" v-on:click="save">Save</button>
        <button class="btn btn-sm" v-on:click="close">Close</button>
      </template>
    </modal>
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
    ...mapMutations("backupList", ["updateItem"])
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
  itemEntry!: BackupItem;
  displayEditModal: boolean = false;
  updateBackup!: (item: BackupItem) => Promise<BackupItemResponse>;
  updateItem!: (item: BackupItem) => void;

  save() {
    const date = new Date(this.itemEntry.date);
    this.itemEntry.date = date.toISOString().slice(0, 10);
    this.updateBackup(this.itemEntry).then((response: BackupItemResponse) => {
      this.close();
      this.updateItem(response.data.item);
      this.$emit('updated', response.data.item);
    });
  }

  close() {
    this.displayEditModal = false;
  }

  editItem(item: BackupItem) {
    this.displayEditModal = true;
  }

  created() {
    this.itemEntry = JSON.parse(JSON.stringify(this.item));
  }
}
</script>
