import {Action, Mutation, VuexModule} from "vuex-module-decorators";
import {Response} from "@/models/Response";

export default abstract class AsyncRequest extends VuexModule {
  error!: string;
  responseErrors: string[] = [];
  loading: boolean = false;

  @Mutation
  setError(error?: Error) {
    this.error = (error && error.message) || "";
  }

  @Mutation
  setResponseErrors(responseErrors: []) {
    this.responseErrors = responseErrors;
  }

  @Mutation
  setLoading(loading: boolean) {
    this.loading = loading;
  }

  @Action
  async asyncRequest(axiosRequestFactory: CallableFunction): Promise<Response> {
    this.context.commit("setLoading", true);
    this.context.commit("setResponseErrors", {});
    return await new Promise<Response>((resolve, reject) => {
      return axiosRequestFactory(resolve, reject)
        .catch(() => reject)
        .finally(() => {
          this.context.commit("setLoading", false);
        });
    });
  }
}
