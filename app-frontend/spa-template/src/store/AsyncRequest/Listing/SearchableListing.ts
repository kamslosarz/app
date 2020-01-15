import Listing from "./Listing";
import {Action, Mutation} from "vuex-module-decorators";
import {ListResponse} from "@/models/Response";
import {AxiosPromise, AxiosResponse} from "axios";
import {Vue} from "vue-property-decorator";

export abstract class SearchableListing<ItemType> extends Listing<ItemType> {
  abstract searchEndpoint: string;
  keyword: string = "";

  @Mutation
  updateKeyword(keyword: string) {
    this.keyword = keyword;
  }

  @Action
  async search(offset: number = 0): Promise<AxiosPromise> {
    return this.context.dispatch(
      "asyncRequest",
      (resolve: Function, reject: Function) => {
        let formData = new FormData();
        formData.append("keyword", this.keyword);
        let searchEndpoint = offset
          ? this.searchEndpoint + "/" + offset
          : this.searchEndpoint;
        return Vue.axios
          .post<ListResponse<ItemType>>(searchEndpoint, formData, {
            headers: { "Content-Type": "application/x-www-form-urlencoded" }
          })
          .then((response: AxiosResponse<ListResponse<ItemType>>) => {
            let listResponse: ListResponse<ItemType> = response.data;
            if (!listResponse.success) {
              this.context.commit("setErrors", listResponse.errors);
              reject(listResponse.errors);
            } else {
              this.context.commit("setItems", listResponse.data.items);
              this.context.commit(
                "updatePagination",
                listResponse.data.pagination
              );
              resolve(listResponse);
            }
          });
      }
    );
  }
}
