import {Action, Mutation, VuexModule} from "vuex-module-decorators";

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

  @Action
  async tryRequest(requestCallback: CallableFunction) {
    this.context.commit("setLoading", true);
    this.context.commit("setErrors", {});
    try {
      return await requestCallback();
    } catch (error) {
      this.context.commit("setError", [error]);
    } finally {
      this.context.commit("setLoading", false);
    }
  }
}
