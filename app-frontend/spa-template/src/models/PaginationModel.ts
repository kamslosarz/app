export interface PaginationInterface {
  total: number;
  perPage: number;
  offset: number;
}

export interface Page {
  title: string;
  page: number,
  disabled: boolean
}
