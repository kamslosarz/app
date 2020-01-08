import {Vue} from "vue-property-decorator";
import {AuthTokenResponse} from "@/models/Response";
import {AxiosResponse} from "axios";

export default class AuthService {
  authKey: string;
  token: string | null = null;

  constructor(authKey: string) {
    this.authKey = authKey;
    let token = sessionStorage.getItem("authToken");
    if (typeof token === "string" && token) {
      this.token = token;
    } else {
      this.token = null;
    }
  }

  setToken(token: string) {
    this.token = token;
    sessionStorage.setItem("authToken", token);
  }

  getToken(): string | null {
    return this.token;
  }

  hasToken(): boolean {
    return this.token !== null;
  }

  generateAuthToken(): Promise<AuthTokenResponse> {
    return new Promise((resolve, reject) => {
      Vue.axios
        .get<AuthTokenResponse>("/auth/token", {
          headers: {
            authKey: this.authKey
          }
        })
        .then((response: AxiosResponse) => {
          let authTokenResponse: AuthTokenResponse = response.data;
          this.setToken(authTokenResponse.data.token);
          resolve(authTokenResponse);
        });
    });
  }
}
