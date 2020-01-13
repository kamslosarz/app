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
        <div class="toast-body">
          <div class="alert" :class="['alert-' + message.type]">
            {{ message.body }}
          </div>
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
    let secondsAgo: number = Math.floor(
      (Date.now() - message.date.getTime()) / 1000
    );

    return message.duration > secondsAgo;
  }

  getDateAgo(date: Date): string {
    let secondsAgo = Math.floor((Date.now() - date.getTime()) / 1000);
    let intervals = [
      { period: "years", seconds: 31536000 },
      { period: "months", seconds: 2592000 },
      { period: "weeks", seconds: 604800 },
      { period: "days", seconds: 86400 },
      { period: "hours", seconds: 3600 },
      { period: "minutes", seconds: 60 },
      { period: "seconds", seconds: 1 }
    ];

    for (let index in intervals) {
      let interval: { period: string; seconds: number } = intervals[index];
      let intervalsInDate = Math.floor(secondsAgo / interval.seconds);
      if (intervalsInDate > 1) {
        return intervalsInDate + " " + interval.period + " ago";
      }
    }

    return "second ago";
  }

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
