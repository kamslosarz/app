import Vue from "vue";
import App from "./App.vue";
import "./registerServiceWorker";
import router from "./router";
import store from "./store";
import axios from "axios";
import VueAxios from "vue-axios";
import {auth} from "./services/services";

Vue.use(VueAxios, axios);
Vue.axios.defaults.baseURL = "http://app.backup.dev.com/api";
Vue.axios.interceptors.response.use(
  response => response,
  error => {
    console.error(error);
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
