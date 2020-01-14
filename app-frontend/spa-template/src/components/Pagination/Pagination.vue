<template>
  <nav aria-label="Page navigation example">
    <ul class="pagination justify-content-end">
      <li v-if="pagination">
        <a class="page-link">
          <small>
            {{ pagination.offset }}
            -
            {{ totalShownRecords }}
            of {{ pagination.total }} records
          </small>
        </a>
      </li>
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

  isActive(page: Page): boolean {
    return this.pagination.page === page.page;
  }

  pageSelected(page: Page) {
    this.pagination.page = page.page;
    this.$emit("pageSelected", page);
  }

  get totalShownRecords(): number {
    const total: number = this.pagination.offset + this.pagination.perPage;
    return total > this.pagination.total ? this.pagination.total : total;
  }

  get totalPages(): number {
    if (this.pagination) {
      return Math.ceil(this.pagination.total / this.pagination.perPage);
    }
    return 0;
  }

  get pages(): Page[] {
    let pages: Page[] = [];
    if (this.pagination) {
      if (this.totalPages > 1) {
        const displayedPages = 5;
        const firstPage: number =
          this.pagination.page < 3 || this.totalPages < displayedPages
            ? 0
            : this.pagination.page < this.totalPages - 2
            ? this.pagination.page - 2
            : this.totalPages - 5;

        const lastPage: number =
          firstPage === 0 ? displayedPages : this.pagination.page + 3;

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
          page: this.pagination.page - 1,
          disabled: this.pagination.page - 1 < 0
        });
        pages.push({
          title: "»",
          page: this.pagination.page + 1,
          disabled: this.pagination.page + 1 === this.totalPages
        });
      }
    }

    return pages;
  }
}
</script>
