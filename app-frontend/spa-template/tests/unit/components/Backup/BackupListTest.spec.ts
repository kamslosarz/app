import {createLocalVue, mount, shallowMount} from "@vue/test-utils";
import Vuex from "vuex";
import BackupList from "@/components/Backup/BackupList.vue";
import {BackupItem, BackupListResponse} from "@/models/Backup";

const localVue = createLocalVue();
localVue.use(Vuex);

describe("Backup list tests", () => {
  let modules: any;
  let items: BackupItem[];

  beforeEach(() => {
    jest.useFakeTimers();
    modules = {
      backupList: {
        namespaced: true,
        actions: {
          getBackupList: jest.fn(),
          search: jest.fn()
        },
        mutations: {
          updateKeyword: jest.fn(),
          updatePage: jest.fn()
        },
        state: {
          items: {},
          pagination: undefined,
          page: 0,
          keyword: ""
        }
      }
    };
    items = [
      {
        date: "backup date",
        description: "backup desc",
        name: "backup name",
        id: 1
      },
      {
        date: "backup date",
        description: "backup desc",
        name: "backup name",
        id: 2
      },
      {
        date: "backup date",
        description: "backup desc",
        name: "backup name",
        id: 3
      }
    ];
  });

  it("create list component with default data", async () => {
    const wrapper = mount(BackupList, {
      store: new Vuex.Store({ modules }),
      localVue,
      stubs: {
        "backup-edit": "<div :class=\"['backup-edit-'+item.id]\"></div>",
        "backup-delete": "<div :class=\"['backup-delete-'+item.id]\"></div>",
        pagination: '<div class="pagination"></div>',
        search: '<div class="search">{{ onLoadKeyword }}</div>'
      }
    });

    //assert search displayed
    expect(wrapper.find(".search").exists()).toBeTruthy();
    //assert onload keyword
    expect(modules.backupList.actions.getBackupList).toBeCalledTimes(1);
    //assert list loaded on mount
    const keyword = "search keyword";
    modules.backupList.state.keyword = keyword;
    expect(wrapper.find(".search").text()).toStrictEqual(keyword);
    //assert list displayed
    expect(wrapper.find("table.backup-list").exists()).toBeTruthy();
    //assert elements in list
    modules.backupList.state.items = items;
    //assert list elements and headers displayed
    expect(wrapper.findAll("table.backup-list tr").length).toBe(4);
    //assert list element is editable and removable
    expect(wrapper.find(".backup-edit-1").exists()).toBeTruthy();
    expect(wrapper.find(".backup-delete-1").exists()).toBeTruthy();
    //assert pagination exists
    expect(wrapper.find(".pagination").exists()).toBeTruthy();
  });

  it("search for backups", async () => {
    const wrapper = shallowMount(BackupList, {
      store: new Vuex.Store({ modules }),
      localVue
    });

    //emit search
    const keyword = "backup name";
    let search = wrapper.find("search-stub");
    modules.backupList.state.keyword = keyword;
    search.vm.$emit("searched", keyword);
    await search.vm.$nextTick();

    //assert searched
    expect(search.emitted().searched).toBeTruthy();
    expect(modules.backupList.mutations.updatePage).toBeCalledWith(
      expect.anything(),
      0
    );
    expect(modules.backupList.mutations.updateKeyword).toBeCalledWith(
      expect.anything(),
      keyword
    );
    expect(modules.backupList.actions.search).toBeCalledWith(
      expect.anything(),
      0
    );
  });

  it("dismiss search", async () => {
    const wrapper = shallowMount(BackupList, {
      store: new Vuex.Store({ modules }),
      localVue
    });

    //emit search
    modules.backupList.state.keyword = "backup name";
    let search = wrapper.find("search-stub");
    search.vm.$emit("searched", "");
    await search.vm.$nextTick();

    //assert searched
    expect(search.emitted().searched).toBeTruthy();
    expect(modules.backupList.mutations.updatePage).toBeCalledWith(
      expect.anything(),
      0
    );
    expect(modules.backupList.mutations.updateKeyword).toBeCalledWith(
      expect.anything(),
      ""
    );
    expect(modules.backupList.actions.getBackupList).toBeCalledWith(
      expect.anything(),
      0
    );
  });

  it("update item on list when edited", async () => {
    const wrapper = shallowMount(BackupList, {
      store: new Vuex.Store({ modules }),
      localVue
    });

    modules.backupList.state.items = items;
    const item: BackupItem = items[0];
    const editedItem: BackupItem = {
      id: item.id,
      name: "name",
      description: "desc",
      date: "date"
    };

    const tableRow = wrapper.find("table.backup-list tbody tr");

    //assert first element name
    expect(tableRow.find("td").text()).toStrictEqual("#1 backup name");
    //emit update first element
    modules.backupList.state.items[0] = editedItem;
    tableRow.find("backup-edit-stub").vm.$emit("updated", editedItem);
    //assert name changed
    expect(tableRow.text()).toStrictEqual(
      "#" +
        Object.values(editedItem)
          .toString()
          .replace(/,/g, " ")
    );
  });

  it("reload after remove item from list when deleted", async () => {
    const response: BackupListResponse = {
      success: true,
      errors: [],
      data: {
        pagination: {
          page: 0,
          perPage: 10,
          total: 100,
          offset: 0
        },
        items: items
      }
    };
    modules.backupList.state.items = items;
    modules.backupList.actions.getBackupList = jest
      .fn()
      .mockResolvedValue(response);

    const wrapper = shallowMount(BackupList, {
      store: new Vuex.Store({ modules }),
      localVue
    });

    const editStub = wrapper.find("table tbody tr td backup-delete-stub");
    editStub.vm.$emit("removed", items[0]);

    expect(modules.backupList.actions.getBackupList).toBeCalledWith(
      expect.anything(),
      0
    );
  });

  it("search after remove item from list when deleted", async () => {
    const response: BackupListResponse = {
      success: true,
      errors: [],
      data: {
        pagination: {
          page: 0,
          perPage: 10,
          total: 100,
          offset: 0
        },
        items: items
      }
    };
    modules.backupList.state.items = items;
    modules.backupList.state.keyword = "some kw";
    modules.backupList.actions.search = jest.fn().mockResolvedValue(response);

    const wrapper = shallowMount(BackupList, {
      store: new Vuex.Store({ modules }),
      localVue
    });

    const deleteStub = wrapper.find("table tbody tr td backup-delete-stub");
    deleteStub.vm.$emit("removed", items[0]);

    expect(modules.backupList.actions.search).toBeCalledWith(
      expect.anything(),
      0
    );
  });

  it("load specific offset after remove item", async () => {
    const response: BackupListResponse = {
      success: true,
      errors: [],
      data: {
        pagination: {
          page: 5,
          perPage: 10,
          total: 100,
          offset: 0
        },
        items: items
      }
    };
    modules.backupList.state.items = items;
    modules.backupList.state.pagination = response.data.pagination;
    modules.backupList.state.keyword = "some kw";
    modules.backupList.actions.search = jest.fn().mockResolvedValue(response);

    const wrapper = shallowMount(BackupList, {
      store: new Vuex.Store({ modules }),
      localVue
    });

    const deleteStub = wrapper.find("table tbody tr td backup-delete-stub");
    deleteStub.vm.$emit("removed", items[0]);

    expect(modules.backupList.actions.search).toBeCalledWith(
      expect.anything(),
      50
    );
  });

  it("load previous page if last page item deleted", async () => {
    const response: BackupListResponse = {
      success: true,
      errors: [],
      data: {
        pagination: {
          page: 3,
          perPage: 1,
          total: 3,
          offset: 1
        },
        items: items
      }
    };
    modules.backupList.state.items = [items[2]];
    modules.backupList.state.keyword = "some kw";
    modules.backupList.actions.search = jest.fn().mockResolvedValue(response);
    modules.backupList.state.pagination = response.data.pagination;
    modules.backupList.mutations.updatePage = jest
      .fn()
      .mockImplementation(() => {
        modules.backupList.state.pagination = {
          page: 2,
          perPage: 1,
          total: 2,
          offset: 1
        };
      });

    const wrapper = shallowMount(BackupList, {
      store: new Vuex.Store({ modules }),
      localVue
    });
    const tableStub = wrapper.find(".backup-list");
    const deleteStub = tableStub.find("tbody tr td backup-delete-stub");
    expect(tableStub.findAll("tbody tr").length).toBe(1);

    deleteStub.vm.$emit("removed", items[0]);
    await deleteStub.vm.$nextTick();
    await jest.runAllTicks();
    await jest.runAllTimers();

    expect(modules.backupList.mutations.updatePage).toBeCalledWith(
      expect.anything(),
      2
    );
    //assert list loaded
    expect(modules.backupList.actions.search).toHaveBeenNthCalledWith(
      1,
      expect.anything(),
      3
    );
    //assert list reloaded after delete
    expect(modules.backupList.actions.search).toHaveBeenNthCalledWith(
      2,
      expect.anything(),
      3
    );
    //assert previous page loaded after delete last page item
    expect(modules.backupList.actions.search).toHaveBeenNthCalledWith(
      3,
      expect.anything(),
      2
    );
  });

  it("select specific page", async () => {
    modules.backupList.state.pagination = {
      page: 1,
      perPage: 1,
      total: 3,
      offset: 1
    };
    modules.backupList.mutations.updatePage = jest
      .fn()
      .mockImplementation(() => {
        modules.backupList.state.pagination.page = 3;
      });

    const wrapper = shallowMount(BackupList, {
      store: new Vuex.Store({ modules }),
      localVue
    });

    const paginationStub = wrapper.find("pagination-stub");
    paginationStub.vm.$emit("pageSelected", {
      page: 3
    });

    await paginationStub.vm.$nextTick();
    await jest.runAllTimers();
    await jest.runAllTicks();

    expect(modules.backupList.mutations.updatePage).toBeCalledWith(
      expect.anything(),
      3
    );
    expect(modules.backupList.actions.getBackupList).toHaveBeenNthCalledWith(
      1,
      expect.anything(),
      1
    );
    expect(modules.backupList.actions.getBackupList).toHaveBeenNthCalledWith(
      2,
      expect.anything(),
      3
    );
  });
});
