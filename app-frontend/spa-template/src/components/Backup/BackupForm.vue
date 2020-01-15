<template>
  <form>
    <div class="form-group">
      <label for="name">Backup name</label>
      <input
        name="name"
        v-model="entry.name"
        id="name"
        class="form-control"
        placeholder="Enter backup name"
        :class="{ 'is-invalid': errors.name }"
      />
      <small class="form-text text-muted">
        Type a name for your backup. Allowed special chars !@&%@!:
      </small>
      <div class="invalid-feedback">
        <span v-for="(error, key) in errors.name" v-bind:key="key">
          {{ error }}
        </span>
      </div>
    </div>
    <div class="form-group">
      <label for="description">Backup description</label>
      <textarea
        name="description"
        id="description"
        class="form-control"
        placeholder="Enter backup description"
        v-model="entry.description"
        :class="{ 'is-invalid': errors.description }"
      />
      <small class="form-text text-muted">
        Type a description for your backup. Allowed 255 chars.
      </small>
      <div class="invalid-feedback">
        <span v-for="(error, key) in errors.description" v-bind:key="key">
          {{ error }}
        </span>
      </div>
    </div>
    <div class="form-group">
      <label for="name">Backup date</label>
      <datepicker
        id="date"
        class="datepicker form-control"
        placeholder="Select a backup date"
        v-model="entry.date"
        name="date"
        format="MM/dd/yyyy"
        :class="{ 'is-invalid': errors.date }"
      />
      <small id="nameHelp" class="form-text text-muted">
        Type a date of your backup.
      </small>
      <div class="invalid-feedback">
        <span v-for="(error, key) in errors.date" v-bind:key="key">
          {{ error }}
        </span>
      </div>
    </div>
    <slot name="form-bottom" />
  </form>
</template>

<script lang="ts">
  import {Component, Prop, Vue} from "vue-property-decorator";
  import {BackupItem} from "@/models/Backup";
  import Datepicker from "vuejs-datepicker";

  @Component({
  components: {
    Datepicker
  }
})
export default class BackupForm extends Vue {
  @Prop({
    required: true
  })
  entry!: BackupItem;
  @Prop({
    required: true,
    default() {
      return {};
    }
  })
  errors!: {};
}
</script>

<style lang="scss">
input[name="date"] {
  width: 100%;
  height: 100%;
  border: none;
}
</style>
