<template>
  <div class="toasts">
    <transition
      name="fade"
      v-for="message in messages"
      v-bind:key="message.title"
    >
      <div class="toast" v-if="hasDurationLeft(message)">
        <div class="toast-header">
          <strong class="mr-auto">{{ message.title }}</strong>
          <small class="ml-2"> {{ getDateAgo(message.date) }}</small>
          <button type="button" class="ml-2 mb-1 close">
            <span v-on:click="remove(message)">&times;</span>
          </button>
        </div>
        <div class="toast-body" :class="['alert-' + message.type]">
          {{ message.body }}
        </div>
      </div>
    </transition>
  </div>
</template>

<script lang="ts">
  import {Component, Vue} from "vue-property-decorator";
  import {ToastMessage} from "@/models/ToastMessage";
  import {mapMutations, mapState} from "vuex";

  @Component({
  methods: {
    ...mapMutations("toast", ["removeMessage"])
  },
  computed: {
    ...mapState("toast", ["messages"])
  }
})
export default class Toast extends Vue {
  removeMessage!: (toastMessage: ToastMessage) => {};
  messages!: ToastMessage[];

  mounted() {
    this.startDateTimer();
  }

  startDateTimer() {
    setInterval(() => {
      if (this.messages.length) {
        this.$forceUpdate();
      }
    }, 1000);
  }

  hasDurationLeft(message: ToastMessage): boolean {
    const secondsAgo: number = Math.floor(
      (Date.now() - message.date.getTime()) / 1000
    );

    return message.duration > secondsAgo;
  }

  getDateAgo(date: Date): string {
    const secondsAgo = Math.floor((Date.now() - date.getTime()) / 1000);
    const intervals = [
      { period: "years", seconds: 31536000 },
      { period: "months", seconds: 2592000 },
      { period: "weeks", seconds: 604800 },
      { period: "days", seconds: 86400 },
      { period: "hours", seconds: 3600 },
      { period: "minutes", seconds: 60 },
      { period: "seconds", seconds: 1 }
    ];

    for (const index in intervals) {
      const interval: { period: string; seconds: number } = intervals[index];
      const intervalsInDate = Math.floor(secondsAgo / interval.seconds);
      if (intervalsInDate > 1) {
        return intervalsInDate + " " + interval.period + " ago";
      }
    }

    return "second ago";
  }

  remove(removeMessage: ToastMessage) {
    this.removeMessage(removeMessage);
  }
}
</script>

<style lang="scss">
.toasts {
  z-index: 99999;
  position: fixed;
  right: 10px;
  top: 10px;
}
.toast {
  opacity: 1;
}
</style>
