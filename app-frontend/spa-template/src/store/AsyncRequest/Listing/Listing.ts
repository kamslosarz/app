import {Action, Mutation} from "vuex-module-decorators";
import {Vue} from "vue-property-decorator";
import AsyncRequest from "@/store/AsyncRequest/AsyncRequest";
import {ListResponse} from "@/models/Response";
import {PaginationInterface} from "@/models/PaginationModel";

export default abstract class Listing<ItemType> extends AsyncRequest {
  abstract listEndpoint: string;
  items: Array<ItemType> = [];
  pagination: PaginationInterface | null = null;

  @Mutation
  setItems(items: ItemType[]) {
    this.items = items;
  }

  @Mutation
  updatePagination(pagination: PaginationInterface) {
    this.pagination = pagination;
  }

  @Mutation
  updatePage(page: number) {
    if (this.pagination) {
      this.pagination.page = page;
    }
  }

  @Action
  async getItems(offset: number = 0) {
    return await this.context.dispatch("tryRequest", async () => {
      let listEndpoint = offset
        ? this.listEndpoint + "/" + offset
        : this.listEndpoint;
      const listResponse = await Vue.axios.get<ListResponse<ItemType>>(
        listEndpoint
      );
      if (!listResponse.data.success) {
        this.context.commit("setErrors", listResponse.data.errors);
      } else {
        this.context.commit("setItems", listResponse.data.data.items);
        this.context.commit(
          "updatePagination",
          listResponse.data.data.pagination
        );
      }

      return listResponse.data;
    });
  }

  @Mutation
  itemDeleted(deletedItem: ItemType) {
    this.items = this.items.filter(item => item !== deletedItem);
  }
}
