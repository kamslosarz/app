import {createLocalVue} from "@vue/test-utils";
import Vuex from "vuex";

const localVue = createLocalVue();
localVue.use(Vuex);

describe("Backup list tests", () => {
  let modules: any;

  beforeEach(() => {
    modules = {
      backupItem: {
        namespaced: true,
        actions: {}
      }
    };
  });
  it("create list component with default data", () => {
    // const wrapper = mount(BackupList, {
    //   store: new Vuex.Store({ modules }),
    //   localVue
    // });
  });
});
