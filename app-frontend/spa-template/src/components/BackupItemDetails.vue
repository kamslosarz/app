<template>
  <div id="Summery" class="tab-pane active">
    <loader :is-loading="loading" />
    <div class="table-responsive panel">
      <table class="table">
        <tbody>
          <tr>
            <td class="text-info"><i class="fa fa-user"></i> Full Name</td>
            <td>{{ item.name }}</td>
          </tr>
          <tr>
            <td class="text-info"><i class="fa fa-user"></i> Description</td>
            <td>{{ item.description }}</td>
          </tr>
          <tr>
            <td class="text-info"><i class="fa fa-user"></i> Date</td>
            <td>{{ item.date }}</td>
          </tr>
          <tr>
            <td>
              <input
                type="button"
                class="btn btn-primary btn-sm mr-1"
                v-on:click="edit(item)"
                value="Edit"
              />
              <input
                type="button"
                class="btn btn-danger btn-sm"
                v-on:click="remove(item)"
                value="Delete"
              />
            </td>
            <td></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script lang="ts">
  import {Component, Prop, Vue} from "vue-property-decorator";
  import {BackupItem, BackupItemDeleteResponse} from "@/models/Backup";
  import {mapActions, mapState} from "vuex";
  import Loader from "@/components/Loader.vue";

  @Component({
  components: {
    Loader
  },
  methods: {
    ...mapActions("backup", ["deleteItem"])
  },
  computed: {
    ...mapState("backup", ["loading"])
  }
})
export default class BackupItemDetails extends Vue {
  loading!: boolean;
  @Prop({
    required: true
  })
  item!: BackupItem;
  deleteItem!: (item: BackupItem) => Promise<BackupItemDeleteResponse>;

  remove(deleteItem: BackupItem): void {
    if (confirm("Are you sure?")) {
      this.deleteItem(deleteItem).then((response: BackupItemDeleteResponse) => {
        this.$emit("removed", deleteItem);
      });
    }
  }

  edit(item: BackupItem): void {
    this.$emit("edit", item);
  }
}
</script>
