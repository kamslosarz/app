import {ItemResponse, ListResponse, Response} from "@/models/Response";

export interface Item {
  id: number;
}
export interface BackupItem extends Item {
  name: string;
  description: string;
  date: string;
}
export interface BackupListResponse extends ListResponse<BackupItem> {}
export interface BackupItemDeleteResponse extends Response {}
export interface BackupItemResponse extends ItemResponse<BackupItem> {
  data: {
    item: BackupItem;
  };
}
