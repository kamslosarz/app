import {Action, Module} from "vuex-module-decorators";
import {NavigationResponse} from "@/models/NavigationItem";
import {AxiosPromise,} from "axios";
import Listing from "@/store/AsyncRequest/Listing/Listing";

@Module({
  namespaced: true
})
export default class NavigationModule extends Listing<NavigationResponse> {
  listEndpoint = "navigations";

  @Action
  getNavigation(): Promise<AxiosPromise> {
    return this.context.dispatch("getItems");
  }
}
