import Item from "../AsyncRequest/Item/Item";
import {BackupItem, BackupItemDeleteResponse, BackupItemResponse} from "@/models/Backup";
import {Action, Module} from "vuex-module-decorators";

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
    return this.context.dispatch("saveItem", item);
  }
}
