import {Response} from "@/models/Response";

export interface AuthTokenResponse extends Response {
  data: {
    token: string;
  };
}
