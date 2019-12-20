import {Action, Module, Mutation} from "vuex-module-decorators";
import {BackupItem, BackupItemDeleteResponse, BackupItemResponse} from "@/models/Backup";
import {Vue} from "vue-property-decorator";
import AsyncRequest from "@/store/AsyncRequest/AsyncRequest";

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
        .then(response => {
          if (response.data.errors) {
            this.context.commit("setResponseErrors", response.data.errors);
          } else {
            this.context.commit("setItem", response.data.item);
            resolve(response.data);
          }
        });
    });
  }

  @Action
  saveItem(item: Object) {
    return this.context.dispatch("asyncRequest", (resolve: Function) => {
      return Vue.axios
        .put<BackupItemResponse>("backup", item)
        .then(response => {
          if (response.data.errors) {
            this.context.commit("setResponseErrors", response.data.errors);
          } else {
            this.context.commit("setItem", response.data.item);
            resolve(response.data);
          }
        });
    });
  }

  @Action
  updateItem(item: Object) {
    return this.context.dispatch("asyncRequest", (resolve: Function) => {
      return Vue.axios
        .post<BackupItemResponse>("backup", item)
        .then(response => {
          if (response.data.errors) {
            this.context.commit("setResponseErrors", response.data.errors);
          } else {
            this.context.commit("setItem", response.data.item);
            resolve(response.data);
          }
        });
    });
  }

  @Action
  deleteItem(item: BackupItem) {
    return this.context.dispatch("asyncRequest", (resolve: Function) => {
      return Vue.axios
        .delete<BackupItemDeleteResponse>("backup/" + item.id)
        .then(response => {
          if (response.data.errors) {
            this.context.commit("setResponseErrors", response.data.errors);
          } else {
            this.context.commit("setItem", null);
            resolve(response.data);
          }
        });
    });
  }
}
