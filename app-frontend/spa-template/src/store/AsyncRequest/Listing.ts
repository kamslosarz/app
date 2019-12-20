import {Action, Mutation} from "vuex-module-decorators";
import {Vue} from "vue-property-decorator";
import {ListResponse, Pagination} from "@/models/Backup";
import AsyncRequest from "@/store/AsyncRequest/AsyncRequest";

export default abstract class Listing<ItemType> extends AsyncRequest {
  abstract listEndpoint: string;
  items: ItemType[] = [];
  pagination!: Pagination;

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
        .then(response => {
          if (response.data.errors) {
            this.context.commit("setResponseErrors", response.data.errors);
          } else {
            this.context.commit("setItems", response.data.items);
            this.context.commit("setPagination", response.data.pagination);
            resolve(response.data);
          }
        });
    });
  }
}
