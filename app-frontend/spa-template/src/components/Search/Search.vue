<template>
  <div class="md-form mt-2">
    <input class="form-control" placeholder="Search" v-model="keyword" />
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
}
</script>
