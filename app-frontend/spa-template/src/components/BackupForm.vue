<template>
  <div class="row">
    <div class="col-md-12">
      <slot name="form-message" />
      <div class="form-group">
        <label for="name">Name:</label>
        <input
          type="text"
          id="name"
          class="form-control "
          v-model="item.name"
          :class="{ 'is-invalid': errors['name'] }"
        />
        <div class="invalid-feedback">
          <span v-for="(item, key) in errors['name']" v-bind:key="key">
            {{ item }}
          </span>
        </div>
      </div>
      <div class="form-group">
        <label for="description">Description:</label>
        <textarea
          id="description"
          class="form-control"
          v-model="item.description"
          :class="{ 'is-invalid': errors['description'] }"
        />
        <div class="invalid-feedback">
          <span v-for="(item, key) in errors['description']" v-bind:key="key">
            {{ item }}
          </span>
        </div>
      </div>
      <div class="form-group">
        <label for="date">Date:</label>
        <datepicker
          id="date"
          class="datepicker form-control"
          :value="item.date"
          name="date"
          @selected="selected"
          format="yyyy-MM-dd"
          :class="{ 'is-invalid': errors['date'] }"
        />
        <div class="invalid-feedback">
          <span
            v-for="(item, key, index) in errors['date']"
            v-bind:key="index"
            >{{ item }}</span
          >
        </div>
      </div>
      <div class="btn-group">
        <input
          type="button"
          class="btn btn-primary btn-sm mr-2"
          value="Save"
          v-on:click="save"
        />
        <slot name="form-buttons" />
        <input
          v-if="cancelAble"
          type="button"
          class="btn btn-sm"
          value="Cancel"
          v-on:click="cancel"
        />
      </div>
    </div>
  </div>
</template>

<script lang="ts">
  import {Component, Prop, Vue} from "vue-property-decorator";
  // @ts-ignore
  import Datepicker from "vuejs-datepicker";
  import {BackupItem} from "@/models/Backup";

  @Component({
  components: {
    Datepicker
  }
})
export default class BackupForm extends Vue {
  @Prop({
    required: true
  })
  item!: BackupItem;
  @Prop()
  errors!: string[];
  @Prop({ required: false, default: false })
  cancelAble!: boolean;

  selected(selectedDate: string) {
    let date = new Date(selectedDate);
    this.item.date = date.toISOString().slice(0, 10);
  }

  save() {
    this.$emit("save", JSON.parse(JSON.stringify(this.item)));
  }

  cancel() {
    this.$emit("cancel");
  }
}
</script>
<style lang="scss">
.datepicker {
  padding: 0;
  margin: 0;
}

.datepicker input {
  border: none;
  height: 100%;
  padding: 9px;
  width: 100%;
}
</style>
