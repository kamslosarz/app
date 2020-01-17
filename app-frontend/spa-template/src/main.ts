import Vue from "vue";
import Vuex from 'vuex';
import App from "./App.vue";
import "./registerServiceWorker";
import router from "./router";
import store from "./store";
import axios, {AxiosResponse} from "axios";
import VueAxios from "vue-axios";
import {auth} from "@/services/services";
import {ErrorResponse} from "@/models/Response";
import Loader from "@/components/Loader.vue";
import ConfirmModal from "@/components/Modal/ConfirmModal.vue";

Vue.component("ConfirmModal", ConfirmModal);
Vue.component("Loader", Loader);
Vue.use(Vuex);

Vue.use(VueAxios, axios);
Vue.axios.defaults.baseURL = "http://app.backup.dev.com/api";
Vue.axios.interceptors.response.use(
  (response: AxiosResponse) => response,
  error => {
    const errorResponse: ErrorResponse = error.response;
    const errors = errorResponse.data.errors;
    const tokenErrors = ["Invalid access token", "Access token expired"];
    if (errors.some(i => tokenErrors.indexOf(i) >= 0)) {
      auth.generateToken().then(() => {
        window.location.reload();
      });
    }
  }
);
Vue.config.productionTip = false;
Vue.axios.interceptors.request.use(config => {
  config.timeout = 5000;
  config.headers["authToken"] = auth.getToken();

  return config;
});

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount("#app");
