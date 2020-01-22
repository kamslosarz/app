// @ts-ignore
import AsyncRequest from "@/store/AsyncRequest/AsyncRequest";
import {Action} from "vuex-module-decorators";
import {DeleteItemResponse, ItemResponse} from "@/models/Response";
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

      return response.data;
    });
  }

  @Action
  async saveItem(item: ItemType) {
    return await this.context.dispatch("tryRequest", async () => {
      const response = await Vue.axios.put<ItemResponse<ItemType>>(
        this.saveEndpoint,
        item
      );
      this.context.commit("setErrors", response.data.errors);

      return response.data;
    });
  }

  @Action
  async updateItem(item: ItemType) {
    return await this.context.dispatch("tryRequest", async () => {
      const response = await Vue.axios.post<ItemResponse<ItemType>>(
        this.updateEndpoint,
        item
      );
      this.context.commit("setErrors", response.data.errors);

      return response.data;
    });
  }
}
