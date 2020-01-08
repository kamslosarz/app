import Listing from "./Listing";
import {Action} from "vuex-module-decorators";
import {ListResponse} from "@/models/Response";
import {AxiosPromise, AxiosResponse} from "axios";
import {Vue} from "vue-property-decorator";
import {SearchPayload} from "@/models/Search";

export abstract class SearchableListing<ItemType> extends Listing<ItemType> {
  abstract searchEndpoint: string;

  @Action
  search(payload: SearchPayload): AxiosPromise {
    return this.context.dispatch("asyncRequest", (resolve: Function, reject: Function) => {
      let formData = new FormData();
      formData.append("keyword", payload.keyword);
      let searchEndpoint = payload.offset
        ? this.searchEndpoint + "/" + payload.offset
        : this.searchEndpoint;

      return Vue.axios
        .post<ListResponse<ItemType>>(searchEndpoint, formData, {
          headers: { "Content-Type": "application/x-www-form-urlencoded" }
        })
        .then((response: AxiosResponse) => {
          let listResponse: ListResponse<ItemType> = response.data;
          if (!listResponse.success) {
            this.context.commit("setResponseErrors", listResponse.errors);
            reject(listResponse.errors);
          } else {
            this.context.commit("setItems", listResponse.data.items);
            this.context.commit("setPagination", listResponse.data.pagination);
            resolve(listResponse);
          }
        });
    });
  }
}
