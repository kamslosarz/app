<template>
  <div class="col-md-12">
    <backup-form :entry="entry" :errors="errors">
      <template v-slot:form-bottom>
        <button class="btn btn-sm btn-primary" v-on:click="save">Save</button>
      </template>
    </backup-form>
  </div>
</template>

<script lang="ts">
  import {Component, Vue} from "vue-property-decorator";
  import BackupForm from "@/components/Backup/BackupForm.vue";
  import {BackupItem} from "@/models/Backup";
  import {mapActions, mapState} from "vuex";

  @Component({
  components: {
    BackupForm
  },
  methods: {
    ...mapActions("backupItem", ["save"])
  },
  computed: {
    ...mapState("backupItem", ["errors"])
  }
})
export default class BackupAdd extends Vue {
  entry!: BackupItem;

  save(event: MouseEvent) {
    event.stopPropagation();
    event.preventDefault();

    this.saveBackup;
  }

  created() {
    this.entry = { date: "", description: "", id: 0, name: "" };
  }
}
</script>
