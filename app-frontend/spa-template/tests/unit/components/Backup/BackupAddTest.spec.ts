import BackupAdd from "@/components/Backup/BackupAdd.vue";
import {createLocalVue, mount, shallowMount} from "@vue/test-utils";
import Vuex from "vuex";
import BackupForm from "@/components/Backup/BackupForm.vue";
import {BackupItem} from "@/models/Backup.js";

const localVue = createLocalVue();
localVue.use(Vuex);

describe("BackupAdd.vue", () => {
  let modules: any;

  beforeEach(() => {
    modules = {
      toast: {
        namespaced: true,
        actions: {
          addToastMessage: jest.fn()
        }
      },
      backupItem: {
        namespaced: true,
        actions: {
          saveBackup: jest.fn()
        },
        mutations: {
          setErrors: jest.fn()
        },
        state: {
          errors: {}
        }
      }
    };
  });

  it("testShouldCreateComponent", () => {
    const wrapper = shallowMount(BackupAdd, {
      store: new Vuex.Store({ modules }),
      localVue,
      stubs: {
        BackupForm: "<div>{{ entry }}</div>"
      }
    });
    expect(wrapper.isVueInstance()).toBeTruthy();
    expect(wrapper.find(BackupForm).exists()).toBe(true);
    let entry: BackupItem = JSON.parse(wrapper.find(BackupForm).text());

    expect(entry.date).toHaveLength(24);
    expect(entry.description).toHaveLength(0);
    expect(entry.name).toHaveLength(0);
    expect(entry.id).toBe(0);
    expect(modules.backupItem.mutations.setErrors).toBeCalledTimes(1);
  });

  it("testShouldSaveEntry", () => {
    modules.backupItem.actions.saveBackup = jest.fn();

    const wrapper = mount(BackupAdd, {
      store: new Vuex.Store({ modules }),
      localVue
    });

    let button = wrapper.find(".btn.btn-sm.btn-primary");
    expect(button.exists()).toBeTruthy();
    button.trigger("click");
    expect(modules.backupItem.actions.saveBackup).toBeCalledTimes(1);
  });

  it("testShouldShowSaveEntryMessage", () => {
    modules.backupItem.actions.saveBackup = jest
      .fn()
      .mockReturnValue(new Promise((resolve, reject) => resolve));

    const wrapper = mount(BackupAdd, {
      store: new Vuex.Store({ modules }),
      localVue
    });
    wrapper.find(".btn.btn-sm.btn-primary").trigger("click");

    expect(modules.backupItem.actions.saveBackup).toBeCalledTimes(1);
    expect(modules.toast.actions.addToastMessage).toBeCalledTimes(1);
  });
});
