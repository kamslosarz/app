import {ListResponse, Response} from "@/models/Response";

export interface Entry {
  id: number | null;
}

export interface BackupItem extends Entry {
  name: string;
  description: string;
  date: string;
}

export interface Pagination {
  total?: number;
  perPage?: number;
  offset?: number;
}

export interface BackupListResponse extends ListResponse<BackupItem> {}

export interface BackupItemResponse extends Response {
  data: {
    item: BackupItem;
  };
}

export interface BackupItemDeleteResponse extends Response {}
