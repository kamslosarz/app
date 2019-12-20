import Vue from "vue";
import Vuex from "vuex";
import BackupListModule from "@/store/Backup/BackupListModule";
import BackupItemModule from "@/store/Backup/BackupItemModule";
import NavigationListModule from "@/store/Navigation/NavigationListModule";

Vue.use(Vuex);

export default new Vuex.Store({
  state: {},
  mutations: {},
  actions: {},
  modules: {
    navigationList: NavigationListModule,
    backupList: BackupListModule,
    backupItem: BackupItemModule
  }
});
