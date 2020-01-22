import Listing from "./Listing";
import {Action, Mutation} from "vuex-module-decorators";
import {ListResponse} from "@/models/Response";
import {Vue} from "vue-property-decorator";

export abstract class SearchableListing<ItemType> extends Listing<ItemType> {
  abstract searchEndpoint: string;
  keyword: string = "";

  @Mutation
  updateKeyword(keyword: string) {
    this.keyword = keyword;
  }

  @Action
  async search(offset: number = 0) {
    return await this.context.dispatch("tryRequest", async () => {
      let formData = new FormData();
      formData.append("keyword", this.keyword);
      let searchEndpoint = offset
        ? this.searchEndpoint + "/" + offset
        : this.searchEndpoint;

      const listResponse = await Vue.axios.post<ListResponse<ItemType>>(
        searchEndpoint,
        formData,
        { headers: { "Content-Type": "application/x-www-form-urlencoded" } }
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
}
