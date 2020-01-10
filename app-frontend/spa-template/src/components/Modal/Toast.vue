<template>
  <div class="toasts">
    <transition name="fade" v-for="message in messages" v-bind:key="message">
      <div class="toast">
        <div class="toast-header">
          <strong class="mr-auto">{{ message.title }}</strong>
          <small class="ml-2"> {{ message.date }}</small>
          <button type="button" class="ml-2 mb-1 close">
            <span v-on:click="remove(message)">&times;</span>
          </button>
        </div>
        <div class="toast-body">
          {{ message.body }}
        </div>
      </div>
    </transition>
  </div>
</template>

<script lang="ts">
  import {Component, Vue} from "vue-property-decorator";
  import {ToastMessage} from "@/models/ToastMessage";
  import {mapActions, mapState} from "vuex";

  @Component({
  methods: {
    ...mapActions("toast", ["removeToastMessage"])
  },
  computed: {
    ...mapState("toast", ["messages"])
  }
})
export default class Toast extends Vue {
  removeToastMessage!: (toastMessage: ToastMessage) => {};

  remove(removeMessage: ToastMessage) {
    this.removeToastMessage(removeMessage);
  }
}
</script>

<style lang="scss">
.toasts {
  position: fixed;
  right: 10px;
  top: 0;
}
.toast {
  opacity: 1;
}
</style>
