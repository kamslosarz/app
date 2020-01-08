import {BackupItem} from "@/models/Backup";
import {Action, Module} from "vuex-module-decorators";
import {SearchableListing} from "@/store/AsyncRequest/SearchableListing";

@Module({
  namespaced: true
})
export default class BackupListModule extends SearchableListing<BackupItem> {
  searchEndpoint: string = "backups/search";
  listEndpoint: string = "backups";

  get itemsList(): BackupItem[] {
    return this.items;
  }

  @Action
  updateItem(updateItem: BackupItem) {
    let items = this.items;
    items[
      this.items.findIndex(item => {
        return item.id === updateItem.id;
      })
    ] = updateItem;

    this.context.commit("setItems", items);
  }
}
