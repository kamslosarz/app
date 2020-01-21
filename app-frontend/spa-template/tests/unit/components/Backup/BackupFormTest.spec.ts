import BackupForm from "@/components/Backup/BackupForm.vue";
import {mount} from "@vue/test-utils";
import {BackupItem} from "@/models/Backup";

describe("Backup form tests", () => {
  const time: number = Date.now();
  let entry: BackupItem;

  beforeEach(() => {
    jest.useFakeTimers();
    jest.spyOn(Date, "now").mockImplementation(() => time);
    entry = {
      name: "entry name",
      description: "entry desc",
      date: new Date(time).toISOString(),
      id: 0
    };
  });

  it("create component with default data", async () => {
    const propsData = {
      entry: entry,
      errors: {}
    };
    const wrapper = mount(BackupForm, { propsData });

    expect(wrapper.props().entry).toStrictEqual(entry);
    expect(wrapper.props().errors).toStrictEqual({});
  });

  it("displays form fields errors", () => {
    const propsData = {
      entry: entry,
      errors: {
        name: ["invalid name", "name not valid"],
        description: ["invalid description", "description not valid"],
        date: ["invalid date", "date not valid"]
      }
    };
    const wrapper = mount(BackupForm, { propsData });

    ["name", "description", "date"].forEach(value => {
      let errors = wrapper.find(".invalid-feedback." + value).findAll("span")
        .wrappers;

      expect([errors[0].text(), errors[1].text()]).toStrictEqual([
        "invalid " + value,
        value + " not valid"
      ]);
    });

    wrapper.setProps({ errors: {} });

    ["name", "description", "date"].forEach(value => {
      let invalidFeedback = wrapper.find(".invalid-feedback." + value);
      expect(invalidFeedback.find("span").exists()).toBeFalsy();
    });
  });
});
