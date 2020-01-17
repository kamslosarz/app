<template>
  <div class="d-inline">
    <transition name="fade">
      <button class="btn btn-primary btn-sm" v-on:click="editItem">
        Edit
      </button>
    </transition>
    <transition name="fade">
      <modal v-if="displayEditModal" @close="close">
        <template v-slot:title> Edit item {{ item.name }} </template>
        <template v-slot:body>
          <backup-form :entry="entry" :errors="errors" />
        </template>
        <template v-slot:buttons>
          <button class="btn btn-sm btn-primary" v-on:click="save">Save</button>
          <button class="btn btn-sm" v-on:click="close">Close</button>
        </template>
      </modal>
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

  save() {
    const date = new Date();
    date.setTime(parseInt(this.entry.date));
    this.entry.date = date.toISOString().slice(0, 10);
    this.updateBackup(this.entry).then((response: BackupItemResponse) => {
      this.close();
      let item: BackupItem = response.data.item;
      this.updateItem(item);
      this.addToastMessage({
        title: "Backup Updated",
        body: "Backup '" + item.name + "' was successfully updated"
      });

      this.$emit("updated", response.data.item);
    });
  }

  close() {
    this.displayEditModal = false;
  }

  editItem(item: BackupItem) {
    this.displayEditModal = true;
  }

  created() {
    this.entry = JSON.parse(JSON.stringify(this.item));
    this.setErrors([]);
  }
}
</script>
