<template>
  <div>
    <loader is-loading="loading" />
    <backup-form item="entry" />
  </div>
</template>
<script lang="ts">
  import {Component, Vue, Watch} from "vue-property-decorator";
  import {BackupItem} from "@/models/Backup";
  import {mapActions, mapState} from "vuex";
  import BackupForm from "@/components/BackupForm.vue";
  import Loader from "@/components/Loader.vue";

  @Component({
  components: {
    Loader,
    BackupForm
  },
  methods: {
    ...mapActions("backupItem", ["getItem"])
  },
  computed: {
    ...mapState("backupItem", ["loading", "item"])
  }
})
export default class EditBackupView extends Vue {
  entry!: BackupItem;
  getItem!: (itemId: number) => void;

  @Watch("item")
  itemUpdated(item: BackupItem) {
    this.$emit("itemUpdated", item);
  }

  created() {
    let id = parseInt(this.$route.params.id);
    this.getItem(id);
  }
}
</script>
