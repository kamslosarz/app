import Vue from "vue";
import VueRouter from "vue-router";
import Index from "../views/Index.vue";
import IndexView from "@/views/IndexView.vue";
import BackupsListView from "@/views/BackupsListView.vue";
import AddBackupView from "@/views/AddBackupView.vue";
import BackupItemEdit from "@/components/BackupItemEdit.vue";

Vue.use(VueRouter);

const routes = [
  {
    path: "/index",
    name: "Index",
    component: IndexView
  },
  {
    path: "/list",
    name: "BackupsList",
    component: BackupsListView
  },
  {
    path: "/add",
    name: "AddBackup",
    component: AddBackupView
  },
  {
    path: "/edit",
    name: "EditBackup",
    component: BackupItemEdit
  }
];

const router = new VueRouter({
  mode: "history",
  base: process.env.BASE_URL,
  routes
});

export default router;
