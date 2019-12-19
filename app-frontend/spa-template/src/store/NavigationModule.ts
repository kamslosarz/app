import {Module} from "vuex-module-decorators";
import {NavigationItem, NavigationResponse} from "@/models/Navigation";
import Listing from "@/store/Listing";

@Module({
  namespaced: true
})
export default class NavigationModule extends Listing<
  NavigationItem,
  NavigationResponse
> {
  listEndpoint: string = "getNavigation";
}
