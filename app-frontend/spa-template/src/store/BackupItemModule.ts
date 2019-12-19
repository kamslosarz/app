import {Action, Module, Mutation} from "vuex-module-decorators";
import {BackupItem, BackupItemResponse} from "@/models/Backup";
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
    return new Promise((resolve, reject) => {
      this.context.commit("setLoading", true);
      Vue.axios
        .get<BackupItemResponse>("getBackup/" + itemId)
        .then(response => {
          if (response.data.errors) {
            this.context.commit("setResponseErrors", response.data.errors);
          } else {
            this.context.commit("setItem", response.data.item);
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
        .put<BackupItemResponse>("addBackup", item)
        .then(response => {
          if (response.data.errors) {
            this.context.commit("setResponseErrors", response.data.errors);
          } else {
            this.context.commit("setItem", response.data.item);
          }
          resolve(response.data);
        })
        .catch(err => {
          this.context.commit("setError", err);
          reject(err);
        })
        .finally(() => {
          this.context.commit("setLoading", false);
        });
    });
  }
}
