import BackupAdd from "@/components/Backup/BackupAdd.vue";
import {createLocalVue, mount, shallowMount} from "@vue/test-utils";
import Vuex from "vuex";
import BackupForm from "@/components/Backup/BackupForm.vue";
import {BackupItem, BackupItemResponse} from "@/models/Backup.js";
import axios from "axios";
import {Vue} from "vue-property-decorator";

jest.mock("axios");
const mockAxios = axios as jest.Mocked<typeof axios>;
const localVue = createLocalVue();
localVue.use(Vuex);

describe("BackupAdd.vue", () => {
  let modules: any;
  const time: number = Date.now();

  beforeEach(() => {
    jest.useFakeTimers();
    jest.spyOn(Date, "now").mockImplementation(() => time);

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

  it("creates the component with default entry", async () => {
    const wrapper = shallowMount(BackupAdd, {
      store: new Vuex.Store({ modules }),
      localVue,
      stubs: {
        BackupForm: "<div>{{ entry }}</div>"
      }
    });

    await jest.runAllTicks();
    await Vue.nextTick();

    expect(wrapper.isVueInstance()).toBeTruthy();
    expect(wrapper.find(BackupForm).exists()).toBe(true);
    let entry: BackupItem = JSON.parse(wrapper.find(BackupForm).text());

    expect(entry).toStrictEqual({
      date: new Date(time).toISOString(),
      description: "",
      name: "",
      id: 0
    });

    expect(modules.backupItem.mutations.setErrors).toBeCalledWith(
      expect.anything(),
      []
    );
  });

  it("save entry and display message", async () => {
    const backupEntry: BackupItem = {
      name: "backup name",
      description: "backup desc",
      date: "10/10/2020",
      id: 0
    };
    const backupItemResponse: BackupItemResponse = {
      errors: [],
      success: true,
      data: {
        item: backupEntry
      }
    };

    modules.backupItem.actions.saveBackup = jest
      .fn()
      .mockResolvedValue(backupItemResponse);

    const wrapper = mount(BackupAdd, {
      store: new Vuex.Store({ modules }),
      localVue,
      stubs: {
        BackupForm: '<form><slot name="form-bottom"/></form>'
      }
    });

    Vue.set(wrapper.vm, "entry", backupEntry);

    let button = wrapper.find(".btn.btn-sm.btn-primary");
    expect(button.exists()).toBeTruthy();
    button.trigger("click");

    await jest.runAllTicks();
    await Vue.nextTick();

    expect(modules.backupItem.actions.saveBackup).toBeCalledWith(
      expect.anything(),
      backupEntry
    );
    expect(modules.toast.actions.addToastMessage).toBeCalledWith(
      expect.anything(),
      {
        body: "Backup " + backupEntry.name + " was successfully added",
        title: "Backup added"
      }
    );
  });
});
