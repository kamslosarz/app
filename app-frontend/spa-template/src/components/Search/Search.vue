<template>
  <div class="md-form mt-3">
    <div class="input-group">
      <input
        class="form-control"
        placeholder="Search"
        name="search"
        v-model="keyword"
      />
      <div class="input-group-append">
        <button
          class="btn btn-outline-secondary dismiss-search"
          v-on:click="dismiss"
        >
          &times;
        </button>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
  import {Component, Prop, Vue, Watch} from "vue-property-decorator";

  @Component({})
export default class Search extends Vue {
  keyword: string = "";
  queue = 0;
  @Prop({
    required: true,
    default: ""
  })
  onLoadKeyword!: string;

  mounted() {
    if (!this.keyword) {
      this.keyword = this.onLoadKeyword;
    }
  }

  @Watch("keyword")
  keywordChanged() {
    const timeout = 1000;
    this.queue++;
    setTimeout(() => {
      this.queue--;
      if (!this.queue) {
        this.$emit("searched", this.keyword);
      }
    }, timeout);
  }

  dismiss() {
    if (this.keyword.length) {
      this.keyword = "";
      this.$emit("searched", this.keyword);
    }
  }
}
</script>

<style lang="scss">
.dismiss-search {
  border: 1px solid #ced4da;
}
</style>
