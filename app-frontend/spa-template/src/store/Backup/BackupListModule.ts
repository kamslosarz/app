import {BackupItem} from "@/models/Backup";
import {Module} from "vuex-module-decorators";
import {SearchableListing} from "@/store/AsyncRequest/SearchableListing";

@Module({
  namespaced: true
})
export default class BackupListModule extends SearchableListing<BackupItem> {
  searchEndpoint: string = "backups/search";
  listEndpoint: string = "backups";

  get itemsList(): BackupItem[]{
    return this.items;
  }
}
