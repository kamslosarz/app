import {Mutation, VuexModule} from "vuex-module-decorators";

export default abstract class AsyncRequest extends VuexModule {
  error!: string;
  responseErrors: string[] = [];
  loading: boolean = false;

  @Mutation
  setError(error?: Error) {
    this.error = (error && error.message) || "";
    console.log(this.error);
  }

  @Mutation
  setResponseErrors(responseErrors: []) {
    this.responseErrors = responseErrors;
  }

  @Mutation
  setLoading(loading: boolean) {
    this.loading = loading;
  }
}
