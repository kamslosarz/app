import Item from "../AsyncRequest/Item/Item";
import {Action, Module} from "vuex-module-decorators";
import {BackupItem, BackupItemDeleteResponse, BackupItemResponse} from "@/models/Backup";

@Module({
  namespaced: true
})
export default class BackupItemModule extends Item<BackupItem> {
  updateEndpoint: string = "/backup";
  saveEndpoint: string = "/backup";
  deleteEndpoint: string = "/backup/{id}";

  @Action
  deleteBackup(item: BackupItem): Promise<BackupItemDeleteResponse> {
    return this.context.dispatch("deleteItem", item.id);
  }

  @Action
  updateBackup(item: BackupItem): Promise<BackupItemResponse> {
    return this.context.dispatch("updateItem", item);
  }

  @Action
  saveBackup(item: BackupItem): Promise<BackupItemResponse> {
    if (item.date) {
      item.date = new Date(item.date).toISOString().slice(0, 10);
    }
    return this.context.dispatch("saveItem", item);
  }
}
