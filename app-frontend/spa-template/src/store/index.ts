import Vue from "vue";
import Vuex from "vuex";
import BackupListModule from "@/store/BackupListModule";
import BackupItemModule from "@/store/BackupItemModule";
import NavigationModule from "@/store/NavigationModule";

Vue.use(Vuex);

export default new Vuex.Store({
  state: {},
  mutations: {},
  actions: {},
  modules: {
    navigation: NavigationModule,
    backupList: BackupListModule,
    backupItem: BackupItemModule
  }
});
