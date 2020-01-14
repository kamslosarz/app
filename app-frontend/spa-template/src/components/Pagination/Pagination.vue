<template>
  <nav aria-label="Page navigation example">
    <ul class="pagination justify-content-end">
      <li
        class="page-item"
        v-for="page in pages"
        v-bind:key="page.title"
        :class="{ disabled: page.disabled, active: isActive(page) }"
      >
        <a class="page-link" href="#" v-on:click="pageSelected(page)">
          {{ page.title }}
        </a>
      </li>
    </ul>
  </nav>
</template>

<script lang="ts">
  import {Component, Prop, Vue} from "vue-property-decorator";
  import {Page, PaginationInterface} from "@/models/PaginationModel";

  @Component({})
export default class Pagination extends Vue {
  @Prop({
    required: true
  })
  pagination!: PaginationInterface;
  @Prop({
    required: false,
    default: 0
  })
  current!: number;

  get pages(): Page[] {
    let pages: Page[] = [];
    if (this.pagination) {
      if (this.totalPages > 1) {
        const displayedPages = 5;
        const firstPage: number =
          this.current < 3 || this.totalPages < displayedPages
            ? 0
            : this.current < this.totalPages - 2
            ? this.current - 2
            : this.totalPages - 5;

        const lastPage: number =
          firstPage === 0 ? displayedPages : this.current + 3;

        pages = Array(this.totalPages)
          .fill({ disabled: false, page: 0, title: "" })
          .map((value: Page, index: number) => {
            return {
              title: (index + 1).toString(),
              page: index,
              disabled: false
            };
          })
          .slice(firstPage, lastPage);

        pages.unshift({
          title: "«",
          page: this.current - 1,
          disabled: this.current - 1 < 0
        });
        pages.push({
          title: "»",
          page: this.current + 1,
          disabled: this.current + 1 === this.totalPages
        });
      }
    }

    return pages;
  }

  isActive(page: Page): boolean {
    return this.current === page.page;
  }

  pageSelected(page: Page) {
    this.current = page.page;
    this.$emit("pageSelected", page);
  }

  get totalPages(): number {
    return Math.round(this.pagination.total / this.pagination.perPage);
  }
}
</script>
