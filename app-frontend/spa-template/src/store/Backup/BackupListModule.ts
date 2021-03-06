import {Action, Module, Mutation} from "vuex-module-decorators";
import {SearchableListing} from "../AsyncRequest/Listing/SearchableListing";
import {BackupItem} from "@/models/Backup";

@Module({
  namespaced: true
})
export default class BackupListModule extends SearchableListing<BackupItem> {
  listEndpoint: string = "/backups";
  searchEndpoint: string = "/backups/search";

  @Mutation
  updateItem(updatedItem: BackupItem) {
    this.items[
      this.items.findIndex(item => {
        return item.id === updatedItem.id;
      })
    ] = updatedItem;
  }

  @Action
  async getBackupList(offset: number = 0) {
    return await this.context.dispatch("getItems", offset);
  }

  @Action
  async searchBackups(offset: number = 0) {
    return await this.context.dispatch("search", offset);
  }
}
