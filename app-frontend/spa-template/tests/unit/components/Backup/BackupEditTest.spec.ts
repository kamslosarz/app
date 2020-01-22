import {createLocalVue, mount} from "@vue/test-utils";
import Vuex from "vuex";
import BackupEdit from "@/components/Backup/BackupEdit.vue";
import {Vue} from "vue-property-decorator";
import {BackupItemResponse} from "@/models/Backup";

const localVue = createLocalVue();
localVue.use(Vuex);

describe("Edit Backup tests", () => {
  const time: number = Date.now();
  let propsData: any;
  let modules: any;

  beforeEach(() => {
    jest.useFakeTimers();
    jest.spyOn(Date, "now").mockImplementation(() => time);

    propsData = {
      item: {
        id: 1,
        name: "backup name",
        description: "backup description",
        date: new Date(time).toISOString().slice(0, 10)
      }
    };
    modules = {
      backupItem: {
        namespaced: true,
        mutations: {
          setErrors: jest.fn()
        },
        actions: {
          updateBackup: jest.fn()
        },
        state: {
          errors: {}
        }
      },
      backupList: {
        namespaced: true,
        mutations: {
          updateItem: jest.fn()
        }
      },
      toast: {
        namespaced: true,
        actions: {
          addToastMessage: jest.fn()
        }
      }
    };
  });

  it("create component with initial entry", () => {
    const wrapper = mount(BackupEdit, {
      propsData,
      localVue,
      store: new Vuex.Store({ modules })
    });

    let button = wrapper.find(".edit-btn");
    expect(button.exists()).toBeTruthy();
    expect(modules.backupItem.mutations.setErrors).toBeCalledTimes(1);
  });

  it("click edit button, displays modal and close", async () => {
    const wrapper = mount(BackupEdit, {
      propsData,
      localVue,
      store: new Vuex.Store({ modules })
    });

    let button = wrapper.find(".edit-btn");
    button.trigger("click");
    await jest.runAllTicks();
    await Vue.nextTick();

    expect(wrapper.find(".modal").exists()).toBeTruthy();

    wrapper.find(".modal").vm.$emit("close");
    await jest.runAllTicks();
    await Vue.nextTick();

    expect(wrapper.find(".modal").exists()).toBeFalsy();
  });

  it("confirm edit modal with errors", async () => {
    let errorResponse: BackupItemResponse = {
      data: {
        item: propsData.item
      },
      success: false,
      errors: []
    };
    modules.backupItem.actions.updateBackup = jest
      .fn()
      .mockReturnValue(errorResponse);

    const wrapper = mount(BackupEdit, {
      propsData,
      localVue,
      store: new Vuex.Store({ modules })
    });

    let editButton = wrapper.find(".edit-btn");
    editButton.trigger("click");
    let saveButton = wrapper.find(".save-btn");
    saveButton.trigger("click");

    await jest.runAllTicks();
    await Vue.nextTick();

    //save clicked
    expect(modules.backupItem.actions.updateBackup).toBeCalledWith(
      expect.anything(),
      propsData.item
    );

    //assert modal still displays
    expect(wrapper.find(".modal").exists()).toBeTruthy();
  });

  it("confirm edit modal and save entry", async () => {
    let backupItemResponse: BackupItemResponse = {
      data: {
        item: propsData.item
      },
      success: true,
      errors: []
    };
    modules.backupItem.actions.updateBackup = jest
      .fn()
      .mockReturnValue(backupItemResponse);

    const wrapper = mount(BackupEdit, {
      propsData,
      localVue,
      store: new Vuex.Store({ modules })
    });

    let editButton = wrapper.find(".edit-btn");
    editButton.trigger("click");
    let saveButton = wrapper.find(".save-btn");
    saveButton.trigger("click");

    await jest.runAllTicks();
    await Vue.nextTick();

    //save clicked
    expect(modules.backupItem.actions.updateBackup).toBeCalledWith(
      expect.anything(),
      propsData.item
    );
    expect(modules.backupList.mutations.updateItem).toBeCalledWith(
      expect.anything(),
      propsData.item
    );
    expect(modules.toast.actions.addToastMessage).toBeCalledWith(
      expect.anything(),
      {
        title: "Backup Updated",
        body: "Backup '" + propsData.item.name + "' was successfully updated"
      }
    );
    expect(wrapper.emitted().updated).toBeTruthy();
    //assert modal closed
    expect(wrapper.find(".modal").exists()).toBeFalsy();
  });

  it("should append form with errors", () => {
    let backupItemResponse: BackupItemResponse = {
      errors: {
        name: ["invalid name"],
        description: ["invalid description"],
        date: ["invalid date"]
      },
      data: {
        item: propsData.item
      },
      success: true
    };

    modules.backupItem.state.errors = backupItemResponse.errors;
    modules.backupItem.actions.updateBackup = jest
      .fn()
      .mockReturnValue(backupItemResponse);

    const wrapper = mount(BackupEdit, {
      propsData,
      localVue,
      store: new Vuex.Store({ modules }),
      stubs: {
        "backup-form": "<div>{{ errors }}</div>"
      }
    });

    Vue.set(wrapper.vm, 'displayEditModal', true);
    let errors = JSON.parse(wrapper.find('.modal-body div').text());

    expect(errors).toStrictEqual(backupItemResponse.errors);
  });
});
