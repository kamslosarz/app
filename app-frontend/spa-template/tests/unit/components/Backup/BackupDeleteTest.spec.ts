import BackupDelete from "@/components/Backup/BackupDelete.vue";
import {mount, shallowMount} from "@vue/test-utils";

describe("backup delete tests", () => {
  let propsData: {} = {};
  const time: number = Date.now();

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
});
