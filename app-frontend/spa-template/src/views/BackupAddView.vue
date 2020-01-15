<template>
  <div class="row">
    <transition name="fade">
      <loader :is-loading="isLoading" />
    </transition>
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
export default class BackupAddView extends Vue {}
</script>
