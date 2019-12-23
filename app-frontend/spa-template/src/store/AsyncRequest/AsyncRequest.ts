import {Action, Mutation, VuexModule} from "vuex-module-decorators";
import {Response} from "@/models/Response";

export default abstract class AsyncRequest extends VuexModule {
  error!: string;
  responseErrors: string[] = [];
  loading: boolean = false;

  @Mutation
  setError(error?: Error) {
    this.error = (error && error.message) || "";
    console.error(this.error);
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
  asyncRequest(axiosRequestFactory: CallableFunction): Promise<Response> {
    this.context.commit("setLoading", true);
    this.context.commit("setResponseErrors", {});
    return new Promise<Response>((resolve, reject) => {
      axiosRequestFactory(resolve, reject)
        .catch((e: Error) => {
          this.context.commit("setError", e);
        })
        .finally(() => {
          this.context.commit("setLoading", false);
        });
    });
  }
}
