import {Vue} from "vue-property-decorator";
import {AxiosResponse} from "axios";
import {AuthTokenResponse} from "@/models/AuthTokenResponse";

export default class AuthService {
  token: string | null = null;
  authKey!: string;

  constructor(authKey: string) {
    this.authKey = authKey;
    if (typeof sessionStorage.getItem("authToken") === "string") {
      //@ts-ignore
      this.setToken(sessionStorage.getItem("authToken"));
    }
  }
  getToken() {
    return this.token;
  }

  setToken(token: string) {
    this.token = token;
    sessionStorage.setItem("authToken", token);
  }

  async generateToken(): Promise<void> {
    return this.generateAuthToken().then((response: AuthTokenResponse) => {
      this.setToken(response.data.token);
    });
  }

  private async generateAuthToken(): Promise<AuthTokenResponse> {
    return await new Promise((resolve, reject) => {
      Vue.axios
        .get<AuthTokenResponse>("/auth/token", {
          headers: {
            authKey: this.authKey
          }
        })
        .then((response: AxiosResponse<AuthTokenResponse>) => {
          let authTokenResponse: AuthTokenResponse = response.data;
          resolve(authTokenResponse);
        })
        .catch(()=>reject);
    });
  }
}
