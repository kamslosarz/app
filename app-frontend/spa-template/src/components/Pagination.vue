<template>
  <nav>
    <ul
      class="pagination pagination-sm justify-content-center"
      v-if="displayPagination"
    >
      <li class="page-item" :class="{ disabled: disablePrevious }">
        <a class="page-link" href="#" v-on:click="selectPage(page - 1)">« </a>
      </li>
      <li
        class="page-item"
        :class="{ active: isActive(page) }"
        v-for="page in pages"
        :key="page"
      >
        <span v-if="isActive(page)" class="page-link">
          {{ page }}
        </span>
        <a v-else class="page-link" href="#" v-on:click="selectPage(page)">
          {{ page }}
        </a>
      </li>
      <li class="page-item" :class="{ disabled: disableNext }">
        <a class="page-link" href="#" v-on:click="selectPage(page + 1)">»</a>
      </li>
    </ul>
  </nav>
</template>

<script lang="ts">
  import {Component, Prop, Vue} from "vue-property-decorator";
  import {Pagination as PaginationInterface} from "@/models/Response";

  @Component({})
export default class Pagination extends Vue {
  @Prop({ required: true })
  pagination!: PaginationInterface;
  page: number = 1;

  isActive(page: number): boolean {
    return this.page === page;
  }

  selectPage(page: number): void {
    this.page = page;
    let offset = this.pagination.perPage
      ? this.pagination.perPage * (page - 1)
      : 0;

    this.$emit("pageChanged", offset);
  }

  get disablePrevious(): boolean {
    return this.page === 1 || this.pagination.total <= this.pagination.perPage;
  }

  get disableNext(): boolean {
    let lastPage = Math.ceil(this.pagination.total / this.pagination.perPage);

    return this.page >= lastPage;
  }

  get pages(): number[] {
    let pagesToShow: number = 5;
    let totalPages =
      Math.ceil(this.pagination.total / this.pagination.perPage) + 1;

    let firstPage: number =
      this.page <= 2 || totalPages <= 5
        ? 1
        : this.page >= totalPages - 2
        ? this.page - 2 - (3 + (this.page - totalPages))
        : this.page - 2;

    return new Array(totalPages)
      .fill(0)
      .map((index, value) => {
        return value;
      })
      .slice(firstPage, firstPage + pagesToShow);
  }

  get displayPagination(): boolean {
    return Math.ceil(this.pagination.total / this.pagination.perPage) > 1;
  }
}
</script>
