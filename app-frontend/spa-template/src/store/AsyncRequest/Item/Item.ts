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
  async deleteItem(itemId: number): Promise<DeleteItemResponse> {
    return this.context.dispatch(
      "asyncRequest",
      (resolve: Function, reject: Function) => {
        return Vue.axios
          .delete<DeleteItemResponse>(
            this.deleteEndpoint.replace("{id}", itemId.toString())
          )
          .then((response: AxiosResponse<DeleteItemResponse>) => {
            let deleteResponse = response.data;
            if (!deleteResponse.success) {
              this.context.commit("setErrors", deleteResponse.errors);
            } else {
              resolve(deleteResponse);
            }
          });
      }
    );
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

  @Action
  async saveItem(item: ItemType) {
    try {
      this.context.commit("setLoading", true);
      const response = await Vue.axios.put<ItemResponse<ItemType>>(
        this.saveEndpoint,
        item
      );
      let savedItem: ItemType = response.data.data.item;
      this.context.commit("setItem", item);

      return savedItem;
    } catch (error) {
      this.context.commit("setError", error);
    } finally {
      this.context.commit("setLoading", false);
    }

    return item;
  }
}
