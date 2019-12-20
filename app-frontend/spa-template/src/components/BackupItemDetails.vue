<template>
  <div id="Summery" class="tab-pane active">
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
                v-on:click="remove(item)"
                value="Delete"
              />
              <input
                type="button"
                class="btn btn-secondary btn-sm"
                v-on:click="edit(item)"
                value="Edit"
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
  import {BackupItem} from "@/models/Backup";

  @Component
export default class BackupItemDetails extends Vue {
  @Prop({
    required: true
  })
  item!: BackupItem;

  remove(item: BackupItem): void {
    this.$emit("remove", item);
  }

  edit(item: BackupItem): void {
    this.$emit("edit", item);
  }
}
</script>
