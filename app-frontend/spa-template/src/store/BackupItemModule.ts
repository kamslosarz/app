import {Action, Module, Mutation} from "vuex-module-decorators";
import {BackupItem, BackupItemDeleteResponse, BackupItemResponse} from "@/models/Backup";
import AsyncRequest from "@/store/AsyncRequest";
import {Vue} from "vue-property-decorator";

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
    this.context.commit("setLoading", true);
    this.context.commit("setResponseErrors", {});
    return new Promise(resolve => {
      this.context.commit("setLoading", true);
      Vue.axios
        .get<BackupItemResponse>("backup/" + itemId)
        .then(response => {
          if (response.data.errors) {
            this.context.commit("setResponseErrors", response.data.errors);
          } else {
            this.context.commit("setItem", response.data.item);
            resolve(response);
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

  @Action
  saveItem(item: Object) {
    this.context.commit("setLoading", true);
    this.context.commit("setResponseErrors", {});
    return new Promise((resolve, reject) => {
      Vue.axios
        .put<BackupItemResponse>("backup", item)
        .then(response => {
          if (response.data.errors) {
            this.context.commit("setResponseErrors", response.data.errors);
          } else {
            this.context.commit("setItem", response.data.item);
            resolve(response);
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

  @Action
  deleteItem(item: BackupItem) {
    this.context.commit("setLoading", true);
    this.context.commit("setResponseErrors", {});
    return new Promise((resolve, reject) => {
      Vue.axios
        .delete<BackupItemDeleteResponse>("backup/" + item.id)
        .then(response => {
          if (response.data.errors) {
            this.context.commit("setResponseErrors", response.data.errors);
          } else {
            this.context.commit("setItem", null);
            resolve(response);
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
