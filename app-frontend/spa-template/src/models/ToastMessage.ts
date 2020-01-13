export interface ToastMessage {
  title: string;
  body: string;
  date: Date;
  duration: number;
  type: string;
}

export const ToastMessage = {
  DURATION: 10
};
export const ToastMessageTypes = {
  PRIMARY: "primary",
  SECONDARY: "secondary",
  SUCCESS: "success",
  DANGER: "danger",
  WARNING: "warning",
  INFO: "info",
  LIGHT: "light",
  DARK: "dark"
};
