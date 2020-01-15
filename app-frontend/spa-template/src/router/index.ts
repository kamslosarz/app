import Vue from "vue";
import VueRouter from "vue-router";
import Index from "../views/Index.vue";
import IndexView from "@/views/IndexView.vue";
import BackupListView from "@/views/BackupListView.vue";
import BackupAdd from "@/components/Backup/BackupAdd.vue";

Vue.use(VueRouter);

const routes = [
  {
    path: "/",
    name: "Index",
    component: IndexView
  },
  {
    path: "/list",
    name: "BackupsList",
    component: BackupListView
  },
  {
    path: "/add",
    name: "AddBackup",
    component: BackupAdd
  },
  {
    path: "/edit",
    name: "EditBackup"
  }
];

const router = new VueRouter({
  mode: "history",
  base: process.env.BASE_URL,
  routes
});

export default router;
