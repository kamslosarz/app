import {BackupItem} from "@/models/Backup";
import {Module} from "vuex-module-decorators";
import Listing from "@/store/AsyncRequest/Listing";

@Module({
  namespaced: true
})
export default class BackupListModule extends Listing<BackupItem> {
  listEndpoint: string = "getBackupsList";
}
