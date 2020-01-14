import {PaginationInterface} from "@/models/PaginationModel";

export interface Response {
  errors: string[];
  success: boolean;
}

export interface ListResponse<T> extends Response {
  data: {
    items: T[];
    pagination: PaginationInterface;
  };
}

export interface ErrorResponse {
  data: {
    errors: string[];
  };
}

export interface DeleteItemResponse extends Response {}

export interface ItemResponse<ItemType> extends Response {
  data: {
    item: ItemType;
  };
}
