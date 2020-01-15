<template>
  <div>
    <transition name="fade">
      <loader :is-loading="isLoading" />
    </transition>
    <toast />
    <backup-list />
  </div>
</template>

<script lang="ts">
  import {Component, Vue} from "vue-property-decorator";
  import BackupList from "@/components/Backup/BackupList.vue";
  import {mapGetters} from "vuex";
  import Toast from "@/components/Toast/Toast.vue";

  @Component({
  components: {
    BackupList,
    Toast
  },
  computed: {
    ...mapGetters(["isLoading"])
  }
})
export default class BackupListView extends Vue {}
</script>
