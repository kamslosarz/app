export interface Entry {
  id: number | null;
}

export interface BackupItem extends Entry{
  name: string;
  description: string;
  date: string;
}

export interface BackupListResponse{
  errors: [];
  backupItems: BackupItem[];
}

export interface BackupItemResponse {
  errors: [];
  item: BackupItem;
}
