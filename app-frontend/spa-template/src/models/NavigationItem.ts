import {Response} from "@/models/Response";

export interface NavigationItem {
  id: number;
  href: string;
  title: string;
}

export interface NavigationResponse extends Response {
  items: Array<NavigationItem>;
}
