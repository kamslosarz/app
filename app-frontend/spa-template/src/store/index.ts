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
    isLoading(state): boolean {
      return [
        state.navigation.loading,
        state.backupList.loading,
        state.backupItem.loading,
        state.toast.loading
      ].includes(true);
    }
  },
  modules: {
    navigation: NavigationModule,
    backupList: BackupListModule,
    backupItem: BackupItemModule,
    toast: ToastModule
  }
});
