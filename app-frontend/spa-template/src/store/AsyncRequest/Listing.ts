import {Action, Mutation} from "vuex-module-decorators";
import {Vue} from "vue-property-decorator";
import AsyncRequest from "@/store/AsyncRequest/AsyncRequest";
import {AxiosResponse} from "axios";
import {ListResponse, Pagination} from "@/models/Response";

export default abstract class Listing<ItemType> extends AsyncRequest {
  abstract listEndpoint: string;
  items: ItemType[] = [];
  pagination: Pagination | null = null;

  @Mutation
  setItems(items: ItemType[]) {
    this.items = items;
  }

  @Mutation
  setPagination(pagination: Pagination) {
    this.pagination = pagination;
  }

  @Action
  getItems() {
    return this.context.dispatch("asyncRequest", (resolve: Function) => {
      return Vue.axios
        .get<ListResponse<ItemType>>(this.listEndpoint)
        .then((response: AxiosResponse) => {
          let listResponse: ListResponse<ItemType> = response.data;
          if (!listResponse.success) {
            this.context.commit("setResponseErrors", listResponse.errors);
          } else {
            this.context.commit("setItems", listResponse.data.items);
            this.context.commit("setPagination", listResponse.data.pagination);
            resolve(listResponse);
          }
        });
    });
  }
}
