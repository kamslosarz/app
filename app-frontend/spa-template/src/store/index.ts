import Vue from "vue";
import Vuex from "vuex";
import NavigationListModule from "@/store/Navigation/NavigationListModule";
import BackupModule from "@/store/Backup/BackupModule";

Vue.use(Vuex);

export default new Vuex.Store({
  state: {},
  mutations: {},
  actions: {},
  modules: {
    navigationList: NavigationListModule,
    backup: BackupModule,
  }
});
