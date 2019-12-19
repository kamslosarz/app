import {Action, Mutation} from "vuex-module-decorators";
import {Vue} from "vue-property-decorator";
import AsyncRequest from "@/store/AsyncRequest";

export default abstract class Listing<
  ItemType,
  ResponseType
> extends AsyncRequest {
  abstract listEndpoint: string;
  items: ItemType[] = [];

  @Mutation
  setItems(items: ItemType[]) {
    this.items = items;
  }

  @Action
  getItems() {
    this.context.commit("setLoading", true);
    this.context.commit("setResponseErrors", {});
    return new Promise((resolve, reject) => {
      this.context.commit("setLoading", true);
      Vue.axios
        .get<ResponseType>(this.listEndpoint)
        .then(response => {
          // @ts-ignore
          if (response.data.errors) {
            // @ts-ignore
            this.context.commit("setResponseErrors", response.data.errors);
          } else {
            // @ts-ignore
            this.context.commit("setItems", response.data.items);
          }
        })
        .catch(err => {
          this.context.commit("setError", err);
        })
        .finally(() => {
          this.context.commit("setLoading", false);
        });
    });
  }
}
