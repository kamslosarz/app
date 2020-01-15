<template>
  <div>
    <transition name="fade">
      <loader :is-loading="isLoading" />
    </transition>
    <toast />
    <backup-add />
  </div>
</template>

<script lang="ts">
  import {Component, Vue} from "vue-property-decorator";
  import BackupAdd from "@/components/Backup/BackupAdd.vue";
  import {mapGetters} from "vuex";
  import Toast from "@/components/Toast/Toast.vue";

  @Component({
  components: {
    BackupAdd,
    Toast
  },
  computed: {
    ...mapGetters(["isLoading"])
  }
})
export default class BackupAddView extends Vue {}
</script>
