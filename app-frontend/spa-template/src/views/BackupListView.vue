<template>
  <div>
    <transition name="fade">
      <div>
        <loader :is-loading="isLoading" />
      </div>
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
