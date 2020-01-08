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
export interface Pagination {
  total: number;
  perPage: number;
  offset: number;
}

export interface AuthTokenResponse {
  data: {
    token: string;
  };
}
export interface ErrorResponse {
  data: {
    errors: string[];
  };
}
