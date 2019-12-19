import {BackupItem, BackupListResponse} from "@/models/Backup";
import {Module} from "vuex-module-decorators";
import Listing from "@/store/Listing";

@Module({
  namespaced: true
})
export default class BackupListModule extends Listing<
  BackupItem,
  BackupListResponse
> {
  listEndpoint: string = "getBackupList";
}
