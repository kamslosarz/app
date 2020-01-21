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
  async asyncRequest(axiosRequestFactory: CallableFunction): Promise<Response> {
    this.context.commit("setLoading", true);
    this.context.commit("setErrors", {});
    return await new Promise<Response>((resolve, reject) => {
      return axiosRequestFactory(resolve, reject)
        .catch(() => reject)
        .finally(() => {
          this.context.commit("setLoading", false);
        });
    });
  }
}
