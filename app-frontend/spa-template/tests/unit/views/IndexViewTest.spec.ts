import {shallowMount} from "@vue/test-utils";
import IndexView from "@/views/IndexView.vue";

describe("IndexView.vue", () => {
  it("render index view", () => {
    const msg = "HOME PAGE";
    const wrapper = shallowMount(IndexView, {
      propsData: { msg }
    });

    expect(wrapper.text()).toMatch(msg);
  });
});
