<template>
  <modal @close="close">
    <template v-slot:title>
      Are you sure?
    </template>
    <template v-slot:buttons>
      <button
        class="btn-primary btn-sm mr-1"
        v-on:click="confirm"
        ref="confirmButton"
      >
        Yes
      </button>
      <button class="btn btn-sm" v-on:click="close">
        Close
      </button>
    </template>
  </modal>
</template>

<script lang="ts">
  import {Component, Vue} from "vue-property-decorator";
  import Modal from "@/components/Modal/Modal.vue";

  @Component({
  components: {
    Modal
  }
})
export default class ConfirmModal extends Vue {
  created() {
    window.addEventListener("keyup", (keyboardEvent: KeyboardEvent) => {
      if (keyboardEvent.code === "Enter") {
        const confirmButton = this.$refs.confirmButton;
        if (confirmButton) {
          //@ts-ignore
          confirmButton.click();
        }
      }
    });
  }

  confirm() {
    this.$emit("confirm");
    this.$emit("close");
  }

  close() {
    this.$emit("close");
  }
}
</script>

<style lang="scss">
.modal {
  display: block;
  transition: opacity 0.5s;
}
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}
</style>
