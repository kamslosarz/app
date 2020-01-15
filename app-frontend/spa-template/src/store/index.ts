import Vue from "vue";
import Vuex from "vuex";
import NavigationModule from "@/store/Navigation/NavigationModule";
import BackupListModule from "@/store/Backup/BackupListModule";
import BackupItemModule from "@/store/Backup/BackupItemModule";
import ToastModule from "@/store/Toast/ToastModule";

Vue.use(Vuex);

export default new Vuex.Store({
  state: {},
  mutations: {},
  actions: {},
  getters: {
    isLoading(state: {}): boolean {
      for (const moduleName in state) {
        //@ts-ignore
        const module = state[moduleName];
        if (module && module.loading === true) {
          return true;
        }
      }

      return false;
    }
  },
  modules: {
    navigation: NavigationModule,
    backupList: BackupListModule,
    backupItem: BackupItemModule,
    toast: ToastModule
  }
});
