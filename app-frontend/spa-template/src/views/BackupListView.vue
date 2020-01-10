<template>
  <div class="row">
    <backup-list />
  </div>
</template>

<script lang="ts">
    import {Component, Vue} from "vue-property-decorator";
    import Loader from "@/components/Loader.vue";
    import BackupList from "@/components/Backup/BackupList.vue";

    @Component({
  components: {
    Loader,
    BackupList
  }
})
export default class BackupListView extends Vue {}
</script>
