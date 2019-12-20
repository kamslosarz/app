import ResponseType from "@/models/ResposeType";

export interface Entry {
  id: number | null;
}

export interface BackupItem extends Entry {
  name: string;
  description: string;
  date: string;
}

export interface BackupItemResponse extends ResponseType{
  item: BackupItem;
}

export interface BackupItemDeleteResponse extends ResponseType {
  status: string;
}

export interface Pagination {
  total?: number;
  perPage?: number;
  offset?: number;
}

export interface ListResponse<T> extends ResponseType {
  items: T[];
  pagination: Pagination;
}

export interface BackupListResponse extends ListResponse<BackupItem> {}
