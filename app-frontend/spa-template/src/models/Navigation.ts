export interface NavigationItem {
  id: number;
  href: string;
  title: string;
}

export interface NavigationResponse {
  navigationItems: NavigationItem[];
}
