<template>
  <div class="modal overlay-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            <slot name="title" />
          </h5>
          <button class="close" v-on:click="close">
            <span>&times;</span>
          </button>
        </div>
        <div class="modal-body" v-if="shouldDisplayBody">
          <slot name="body" />
        </div>
        <div class="modal-footer">
          <slot name="buttons" />
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
  import {Component, Vue} from "vue-property-decorator";

  @Component({})
export default class Modal extends Vue {
  close() {
    this.$emit("close");
  }

  mounted() {
    window.addEventListener("keyup", (keyboardEvent: KeyboardEvent) => {
      if (keyboardEvent.code === "Escape") {
        this.$emit("close");
      }
    });
  }

  get shouldDisplayBody(): boolean {
    return typeof this.$slots.body !== "undefined";
  }
}
</script>

<style lang="scss">
.overlay-modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: #0000002e;
  z-index: 10;
}
</style>
