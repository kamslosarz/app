import {Action, Mutation} from "vuex-module-decorators";
import {Vue} from "vue-property-decorator";
import AsyncRequest from "@/store/AsyncRequest/AsyncRequest";
import {AxiosPromise, AxiosResponse} from "axios";
import {ListResponse, Pagination} from "@/models/Response";

export default abstract class Listing<ItemType> extends AsyncRequest {
  abstract listEndpoint: string;
  items: Array<ItemType> = [];
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
  async getItems(offset: number = 0): Promise<AxiosPromise> {
    return this.context.dispatch(
      "asyncRequest",
      (resolve: Function, reject: Function) => {
        let listEndpoint = offset
          ? this.listEndpoint + "/" + offset
          : this.listEndpoint;

        return Vue.axios
          .get<ListResponse<ItemType>>(listEndpoint)
          .then((response: AxiosResponse<ListResponse<ItemType>>) => {
            let listResponse: ListResponse<ItemType> = response.data;
            if (!listResponse.success) {
              this.context.commit("setErrors", listResponse.errors);
              reject();
            } else {
              this.context.commit("setItems", listResponse.data.items);
              this.context.commit(
                "setPagination",
                listResponse.data.pagination
              );
              this.context.commit("setLoading", false);
              resolve(listResponse);
            }
          });
      }
    );
  }

  @Action
  itemDeleted(deletedItem: ItemType) {
    this.context.commit(
      "setItems",
      this.items.filter(item => item !== deletedItem)
    );
  }
}