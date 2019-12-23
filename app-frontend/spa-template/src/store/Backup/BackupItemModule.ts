import {Action, Module, Mutation} from "vuex-module-decorators";
import {BackupItem, BackupItemDeleteResponse, BackupItemResponse} from "@/models/Backup";
import {Vue} from "vue-property-decorator";
import AsyncRequest from "@/store/AsyncRequest/AsyncRequest";
import {AxiosResponse} from "axios";
import {Response} from "@/models/Response";

@Module({
  namespaced: true
})
export default class BackupItemModule extends AsyncRequest {
  item: BackupItem | null = null;

  @Mutation
  setItem(item: BackupItem) {
    this.item = item;
  }

  @Action
  getItem(itemId: number) {
    return this.context.dispatch("asyncRequest", (resolve: Function) => {
      return Vue.axios
        .get<BackupItemResponse>("backup/" + itemId)
        .then((response: AxiosResponse) => {
          let itemResponse: BackupItemResponse = response.data;
          if (!itemResponse.success) {
            this.context.commit("setResponseErrors", itemResponse.errors);
          } else {
            this.context.commit("setItem", itemResponse.data.item);
            resolve(itemResponse);
          }
        });
    });
  }

  @Action
  saveItem(item: Object) {
    return this.context.dispatch("asyncRequest", (resolve: Function) => {
      return Vue.axios
        .put<BackupItemResponse>("backup", item)
        .then((response: AxiosResponse) => {
          let itemResponse: BackupItemResponse = response.data;
          if (!itemResponse.success) {
            this.context.commit("setResponseErrors", itemResponse.errors);
          } else {
            this.context.commit("setItem", itemResponse.data.item);
            resolve(itemResponse);
          }
        });
    });
  }

  @Action
  updateItem(item: Object) {
    return this.context.dispatch("asyncRequest", (resolve: Function) => {
      return Vue.axios
        .post<BackupItemResponse>("backup", item)
        .then((response: AxiosResponse) => {
          let itemResponse: BackupItemResponse = response.data;
          if (!itemResponse.success) {
            this.context.commit("setResponseErrors", itemResponse.errors);
          } else {
            this.context.commit("setItem", itemResponse.data.item);
            resolve(itemResponse);
          }
        });
    });
  }

  @Action
  deleteItem(item: BackupItem) {
    return this.context.dispatch("asyncRequest", (resolve: Function) => {
      return Vue.axios
        .delete<BackupItemDeleteResponse>("backup/" + item.id)
        .then((response: AxiosResponse) => {
          let deleteResponse: Response = response.data;
          if (!deleteResponse.success) {
            this.context.commit("setResponseErrors", deleteResponse.errors);
          } else {
            this.context.commit("setItem", null);
            resolve(deleteResponse);
          }
        });
    });
  }
}
