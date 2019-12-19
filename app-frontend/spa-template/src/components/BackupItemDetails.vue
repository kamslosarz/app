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
              <button type="button" class="btn btn-secondary btn-danger" v-on:click="remove(item)">
                Delete
              </button>
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

  remove(item: BackupItem) {
    this.$emit("remove", item);
  }
}
</script>
