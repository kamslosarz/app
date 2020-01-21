// @ts-ignore
import AsyncRequest from "@/store/AsyncRequest/AsyncRequest";
import {Action} from "vuex-module-decorators";
import {DeleteItemResponse, ItemResponse} from "@/models/Response";
import {AxiosResponse} from "axios";
import {Vue} from "vue-property-decorator";

export default abstract class Item<ItemType> extends AsyncRequest {
  abstract deleteEndpoint: string;
  abstract updateEndpoint: string;
  abstract saveEndpoint: string;

  @Action
  async deleteItem(itemId: number) {
    return await this.context.dispatch("tryRequest", async () => {
      const response = await Vue.axios.delete<DeleteItemResponse>(
        this.deleteEndpoint.replace("{id}", itemId.toString())
      );
      this.context.commit("setErrors", response.data.errors);

      return response;
    });
  }

  @Action
  async saveItem(item: ItemType) {
    return await this.context.dispatch("tryRequest", async () => {
      this.context.commit("setLoading", true);
      const response = await Vue.axios.put<ItemResponse<ItemType>>(
        this.saveEndpoint,
        item
      );
      this.context.commit("setErrors", response.data.errors);
      this.context.commit("setItem", response.data.data.item);

      return response;
    });
  }

  @Action
  async updateItem(item: ItemType): Promise<ItemResponse<ItemType>> {
    return this.context.dispatch(
      "asyncRequest",
      (resolve: Function, reject: Function) => {
        return Vue.axios
          .post<ItemResponse<ItemType>>(this.updateEndpoint, item)
          .then((response: AxiosResponse<ItemResponse<ItemType>>) => {
            let itemResponse = response.data;
            if (!itemResponse.success) {
              this.context.commit("setErrors", itemResponse.errors);
            } else {
              resolve(itemResponse);
            }
          });
      }
    );
  }
}
