import {Pagination} from "@/models/Backup";

export interface Response {
  errors: string[];
  success: boolean;
}

export interface ListResponse<T> extends Response {
  data: {
    items: T[];
    pagination: Pagination;
  };
}