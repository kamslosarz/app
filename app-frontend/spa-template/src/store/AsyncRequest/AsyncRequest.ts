import {Mutation, VuexModule} from "vuex-module-decorators";

export default abstract class AsyncRequest extends VuexModule {
  errors: string[] = [];
  loading: boolean = false;

  @Mutation
  setErrors(responseErrors: []) {
    this.errors = responseErrors;
  }

  @Mutation
  setLoading(loading: boolean) {
    this.loading = loading;
  }
}
