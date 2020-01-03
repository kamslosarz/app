import {Module} from "vuex-module-decorators";
import {NavigationItem} from "@/models/Navigation";
import Listing from "@/store/AsyncRequest/Listing";

@Module({
  namespaced: true
})
export default class NavigationListModule extends Listing<NavigationItem> {
  listEndpoint: string = "navigations";
}
