import BackupDelete from "@/components/Backup/BackupDelete.vue";
import {createLocalVue, mount, shallowMount} from "@vue/test-utils";
import Vuex from "vuex";
import {BackupItemDeleteResponse} from "@/models/Backup";
import {Vue} from "vue-property-decorator";

const localVue = createLocalVue();
localVue.use(Vuex);

describe("backup delete tests", () => {
  const time: number = Date.now();
  let propsData: any;
  let modules: any;
  let stubs: any;

  beforeEach(() => {
    jest.useFakeTimers();
    jest.spyOn(Date, "now").mockImplementation(() => time);
    propsData = {
      item: {
        name: "backup name",
        description: "backup description",
        date: new Date(time).toISOString(),
        id: 0
      }
    };
    modules = {
      backupItem: {
        namespaced: true,
        actions: {
          deleteBackup: jest.fn()
        }
      },
      backupList: {
        namespaced: true,
        actions: {
          itemDeleted: jest.fn()
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

  it("create component with default data", () => {
    const stubs = {
      "confirm-modal": '<div class="modal"/>'
    };

    const wrapper = shallowMount(BackupDelete, {
      propsData,
      stubs
    });

    let button = wrapper.find(".delete-btn");
    expect(button.exists()).toBeTruthy();
    expect(wrapper.find(".modal").exists()).toBeFalsy();
  });

  it("show and close modal on delete button click", async () => {
    const stubs = {
      "confirm-modal": '<div class="modal"/>'
    };
    const wrapper = mount(BackupDelete, { propsData, stubs });

    //open modal
    wrapper.find(".delete-btn").trigger("click");
    // close modal
    const modal = wrapper.find(".modal");
    expect(modal.exists()).toBeTruthy();
    modal.vm.$emit("close");

    // assert closed
    expect(wrapper.find(".modal").exists()).toBeFalsy();
  });

  it("show and confirm delete modal", async () => {
    stubs = {
      "confirm-modal": '<div class="modal"/>'
    };
    const backupDeleteItemResponse: BackupItemDeleteResponse = {
      success: true,
      errors: []
    };
    modules.backupItem.actions.deleteBackup = jest
      .fn()
      .mockResolvedValue(backupDeleteItemResponse);

    const wrapper = mount(BackupDelete, {
      propsData,
      stubs,
      localVue,
      store: new Vuex.Store({ modules })
    });

    //open modal
    wrapper.find(".delete-btn").trigger("click");
    // confirm modal
    const modal = wrapper.find(".modal");
    expect(modal.exists()).toBeTruthy();
    modal.vm.$emit("confirm");

    await jest.runAllTicks();
    await Vue.nextTick();

    expect(modules.backupItem.actions.deleteBackup).toBeCalledWith(
      expect.anything(),
      propsData.item
    );
    expect(modules.toast.actions.addToastMessage).toBeCalledWith(
      expect.anything(),
      {
        title: "Backup removed",
        body: "Backup '" + propsData.item.name + "' was successfully removed"
      }
    );
    expect(modules.backupList.actions.itemDeleted).toBeCalledWith(
      expect.anything(),
      propsData.item
    );

    //assert that modal disappeared
    expect(wrapper.find(".modal").exists()).toBeFalsy();

    //assert event emitted
    expect(wrapper.emitted().removed).toBeTruthy();
  });

  it("confirm delete modal with errors", async () => {
    stubs = {
      "confirm-modal": '<div class="modal"/>'
    };
    const backupDeleteItemResponse: BackupItemDeleteResponse = {
      success: false,
      errors: ["invalid backup"]
    };
    modules.backupItem.actions.deleteBackup = jest
      .fn()
      .mockResolvedValue(backupDeleteItemResponse);

    const wrapper = mount(BackupDelete, {
      propsData,
      stubs,
      localVue,
      store: new Vuex.Store({ modules })
    });

    //open modal
    wrapper.find(".delete-btn").trigger("click");
    // confirm modal
    const modal = wrapper.find(".modal");
    expect(modal.exists()).toBeTruthy();
    modal.vm.$emit("confirm");

    await jest.runAllTicks();
    await Vue.nextTick();

    expect(modules.backupItem.actions.deleteBackup).toBeCalledWith(
      expect.anything(),
      propsData.item
    );

    //assert that modal disappeared
    expect(wrapper.find(".modal").exists()).toBeTruthy();

    //assert event emitted
    expect(wrapper.emitted().removed).toBeFalsy();
  });
});
